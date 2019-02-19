<?php
/**
 * Created by PhpStorm.
 * User: kamil
 * Date: 06.10.16
 * Time: 17:18
 */

namespace Message\Controller;

use Message\Form\MessageForm;
use Message\Model\Message;
use Message\Model\MessageTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class MessageController extends AbstractActionController
{
    private $table;
    public function __construct(MessageTable $messageTable)
    {
        $this->table = $messageTable;
    }

    public function indexAction()
    {
        $messages = $this->table->fetchFirstTen();
        $form = new MessageForm();
        $form->get('submit')->setValue('Send');

        return new ViewModel([
            'form' => $form,
            'messages' => $messages
        ]);

    }

    public function saveAction(){
        $form = new MessageForm();

        $request = $this->getRequest();

        $message = new Message();
        $form->setInputFilter($message->getInputFilter());
        parse_str($request->getContent(),$resultArray);
        $form->setData($resultArray);

        if (! $form->isValid()) {
            return new JsonModel(['message' => 0]);
        }

        $message->exchangeArray($form->getData());
        $message->setCreatedAt(time());
        $this->table->saveMessage($message);
        return new JsonModel(['message' => 1]);;
    }

    public function refreshAction(){
        $messages = $this->table->fetchFirstTen();
        $viewModel = new ViewModel([
            'messages' => $messages
        ]);
        $viewModel->setTerminal('true');
        return $viewModel;
    }

}

