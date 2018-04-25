<?php

namespace Center\Model;
use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Select;
use Center\Model\Document;
use Application\Functions\DirectorySizeCounting;

class DocumentTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function saveDocument(Document $document) {
        $data = array(
            'id_type' => $document->id_type,
            'id_specialization' => $document->id_specialization,
            'testing_date' => $document->testing_date,
            'description' => $document->description,
        );
        
        $id = (int)$document->id;
        
        if ($id == 0) {
            $data['id_member'] = $document->id_member;
            $this->tableGateway->insert($data);
            
            return $this->tableGateway->getLastInsertValue();
        }
        else {
            
            if($this->getDocument($id)) {
                $this->tableGateway->update($data, array('id' => $id));
                
                return true;
            }
            else {
                throw new \Exception('Członek rodziny o podanym ID nie istnieje');
            }
            return true;
        }
    }
    
    public function getDocument($id) {
        $id = (int)$id;
        
        $select = new Select('document');
        $where['document.id'] = $id;
        $select->where($where);
        $select->join('document_type', "document_type.id = document.id_type", array('document_type_name' => 'name'), $select::JOIN_LEFT);
        $select->join('specialization', "specialization.id = document.id_specialization", array('document_specialization_name' => 'name'), $select::JOIN_LEFT);
        
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();

        
        
        if(!$row) {
            throw new \Exception('Dokument o ID '.$id.' nie istnieje');
        }
        
        return $row;
    }
    
    public function getMemberDocuments($member_id) {  
        $select = new Select('document');
        $where['id_member'] = $member_id;
        
        $select->where($where);
        $select->join('document_type', "document_type.id = document.id_type", array('document_type_name' => 'name'), $select::JOIN_LEFT);
        $select->join('specialization', "specialization.id = document.id_specialization", array('document_specialization_name' => 'name'), $select::JOIN_LEFT);
        $select->order(array('testing_date' => 'DESC'));
        
        try {
            $rowset = $this->tableGateway->selectWith($select);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }
        
        return $rowset;
    }

    public function deleteDocument($id) {
        $this->tableGateway->delete(array('id' => $id));
        
        return true;
    }
    
    public function deleteMemberDocuments($id) {
        $this->tableGateway->delete(array('id_member' => $id));
        
        return true;
    }
    
    public function countUserDocuments($id) {
        $select = new Select('document');
        $select->columns(array('*' => new \Zend\Db\Sql\Expression('COUNT(*)')));
        
        $select2 = new Select('member');
        $select2->columns(array('id'));
        $where2['member.user_id'] = $id;
        $select2->where($where2);
        
        
        $select->where->in('document.id_member', $select2);
        
        try {
            $count = $this->tableGateway->selectWith($select);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }
        
        return $count->current()['*'][0];
    }
    
    public function countUserFiles($id) {
        $select2 = new Select('member');
        $select2->columns(array('id'));
        $where2['member.user_id'] = $id;
        $select2->where($where2);
        
        try {
            $user_members = $this->tableGateway->selectWith($select2);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }
        
        $count = 0;
        $files_size = 0;
        $directorySizeCounting = new DirectorySizeCounting;
                
        foreach ($user_members as $member) {
            $documents_dir = scandir('private/source/members/'.$member->id.'/docs');
            $files_size = $files_size + $directorySizeCounting->countSize('private/source/members/'.$member->id);
            
            foreach ($documents_dir as $key => $dir) {
                if ($dir != '.' && $dir !='..') {
                    
                    $document_files = scandir('private/source/members/'.$member->id.'/docs/'.$dir);
                    
                    foreach ($document_files as $key => $file) {
                        if ($file != '.' && $file != '..') {
                            $count++;
                        }
                    }
                }
            }
            
        }
        
        return array('files_count' => $count, 'files_size' => $directorySizeCounting->format_size($files_size));
    }
    
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        
        return $resultSet;
    }
}
