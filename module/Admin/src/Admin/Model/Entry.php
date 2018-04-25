<?php

namespace Admin\Model;

class Entry
{
    public $id;
    public $title;
    public $section_id;
    public $summary;
    public $content;
    public $created;
    public $published;
    public $photo;
    public $author;
    
    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->section_id = (isset($data['section_id'])) ? $data['section_id'] : null;
        $this->summary = (isset($data['summary'])) ? $data['summary'] : null;
        $this->content = (isset($data['content'])) ? $data['content'] : null;
        $this->created = (isset($data['created'])) ? $data['created'] : null;
        $this->published = (isset($data['published'])) ? $data['published'] : null;
        $this->photo = (isset($data['photo'])) ? substr($data['photo'], strpos($data['photo'],'/source/')) : '';
        $this->author = (isset($data['author'])) ? $data['author'] : null;
    }
    
    public function getArrayCopy(){
        return get_object_vars($this);
    }
}

?>