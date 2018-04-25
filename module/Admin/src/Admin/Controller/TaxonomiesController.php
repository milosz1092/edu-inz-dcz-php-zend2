<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Form\AddMedicalForm;
use Admin\Model\Medical;
use Admin\Model\DefaultTax;

use Admin\Model\TaxonomiesTable;

class TaxonomiesController extends AbstractActionController
{

    public function indexAction()
    {
        $context = $this->params()->fromRoute('id');
        $post = $this->request->getPost();
        
        $table = new TaxonomiesTable($context, $this->getServiceLocator()->get('dbAdapter'));
        
        $paginator = $table->getAllTax($context, 10); 

        if ($this->params()->fromRoute('page'))
            $page = $this->params()->fromRoute('page');
        
        $paginator->setCurrentPageNumber((int)$page);
        
        $data = array(
            'context' => $context,
            'paginator' => $paginator,
        );

        if (isset($post['delete-tax']) && $post['tax-id'] != 0) {
            $data['deleteSuccess'] = $table->deleteTax($post['tax-id'], $context);
        }
        
        $viewModel = new ViewModel($data);
        return $viewModel;
    }

    public function addMedicalAction()
    {
        $context = $this->params()->fromRoute('id');
        
        $form = new AddMedicalForm();
        $viewModel = new ViewModel(array('form' => $form, 'context' => $context));

        return $viewModel;
    }

    public function processAddMedicalAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(null, array('controller' => 'taxonomies', 'action' => 'index'));
        }
        
        $post = $this->request->getPost();
        $context = $post['context'];
        
        $table = new TaxonomiesTable($context, $this->getServiceLocator()->get('dbAdapter'));
        
        $form = new AddMedicalForm(); 
        $filter = $this->getServiceLocator()->get('Admin\Form\AddMedicalFilter');
        
        $form->setInputFilter($filter->getInputFilter());
        $form->setData($post);

        if(!$form->isValid()) {
            $model = new ViewModel(array(
               'error' => true,
               'form' => $form,
               'context' => $context
            ));

            $model->setTemplate('admin/taxonomies/add-medical');
            return $model;
        }

        $medical = new Medical();
        $medical->exchangeArray($post);

        $table->saveMedical($medical, $context);
        $empty_form = new AddMedicalForm();

        $viewModel = new ViewModel(array('success' => true, 'form' => $empty_form, 'context' => $context));
        $viewModel->setTemplate('admin/taxonomies/add-medical');

        return $viewModel;
    }

    public function addDefaultAction()
    {
        $context = $this->params()->fromRoute('id');
        
        $form = new AddMedicalForm();
        $viewModel = new ViewModel(array('form' => $form, 'context' => $context));

        return $viewModel;
    }

    public function processAddDefaultAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(null, array('controller' => 'taxonomies', 'action' => 'index'));
        }
        
        $post = $this->request->getPost();
        $context = $post['context'];
        
        $table = new TaxonomiesTable($context, $this->getServiceLocator()->get('dbAdapter'));
        
        $form = new AddMedicalForm(); 
        $filter = $this->getServiceLocator()->get('Admin\Form\AddDefaultFilter');
        
        $form->setInputFilter($filter->getInputFilter());
        $form->setData($post);

        if(!$form->isValid()) {
            $model = new ViewModel(array(
               'error' => true,
               'form' => $form,
               'context' => $context
            ));

            $model->setTemplate('admin/taxonomies/add-default');
            return $model;
        }

        $defaultTax = new DefaultTax();
        $defaultTax->exchangeArray($post);

        $table->saveDefault($defaultTax, $context);
        $empty_form = new AddMedicalForm();

        $viewModel = new ViewModel(array('success' => true, 'form' => $empty_form, 'context' => $context));
        $viewModel->setTemplate('admin/taxonomies/add-default');

        return $viewModel;
    }

    public function editMedicalAction()
    {
        $id = $this->params()->fromRoute('id');
        $context = $this->params()->fromRoute('page');
        $table = new TaxonomiesTable($context, $this->getServiceLocator()->get('dbAdapter'));
        
        $medical = $table->getTax($context, $id);
        $form = new AddMedicalForm();
        $form->bind($medical);
        
        $viewModel = new ViewModel(array('form' => $form, 'context' => $context));

        return $viewModel;
    }

    public function processEditMedicalAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(null, array('controller' => 'taxonomies', 'action' => 'index'));
        }
        
        $post = $this->request->getPost();
        $context = $post['context'];
        
        $table = new TaxonomiesTable($context, $this->getServiceLocator()->get('dbAdapter'));
        
        $form = new AddMedicalForm(); 
        $filter = $this->getServiceLocator()->get('Admin\Form\AddMedicalFilter');
        
        $form->setInputFilter($filter->getInputFilter());
        $form->setData($post);

        if(!$form->isValid()) {
            $model = new ViewModel(array(
               'error' => true,
               'form' => $form,
               'context' => $context
            ));

            $model->setTemplate('admin/taxonomies/edit-medical');
            return $model;
        }

        $medical = new Medical();
        $medical->exchangeArray($post);

        $table->saveMedical($medical, $context);

        $viewModel = new ViewModel(array('success' => true, 'form' => $form, 'context' => $context));
        $viewModel->setTemplate('admin/taxonomies/edit-medical');

        return $viewModel;
    }

    public function editDefaultAction()
    {
        $id = $this->params()->fromRoute('id');
        $context = $this->params()->fromRoute('page');
        $table = new TaxonomiesTable($context, $this->getServiceLocator()->get('dbAdapter'));
        
        $medical = $table->getTax($context, $id);
        $form = new AddMedicalForm();
        $form->bind($medical);
        
        $viewModel = new ViewModel(array('form' => $form, 'context' => $context));

        return $viewModel;
    }

    public function processEditDefaultAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(null, array('controller' => 'taxonomies', 'action' => 'index'));
        }
        
        $post = $this->request->getPost();
        $context = $post['context'];
        
        $table = new TaxonomiesTable($context, $this->getServiceLocator()->get('dbAdapter'));
        
        $form = new AddMedicalForm(); 
        $filter = $this->getServiceLocator()->get('Admin\Form\AddDefaultFilter');
        
        $form->setInputFilter($filter->getInputFilter());
        $form->setData($post);

        if(!$form->isValid()) {
            $model = new ViewModel(array(
               'error' => true,
               'form' => $form,
               'context' => $context
            ));

            $model->setTemplate('admin/taxonomies/edit-default');
            return $model;
        }

        $defaultTax = new DefaultTax();
        $defaultTax->exchangeArray($post);

        $table->saveDefault($defaultTax, $context);

        $viewModel = new ViewModel(array('success' => true, 'form' => $form, 'context' => $context));
        $viewModel->setTemplate('admin/taxonomies/edit-default');

        return $viewModel;
    }


}

