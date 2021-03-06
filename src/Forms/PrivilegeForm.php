<?php

namespace Authorizator\Forms;

use Authorizator\Drivers\UniqueConstraintViolationException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Security\IAuthorizator;


/**
 * Class PrivilegeForm
 *
 * @author  geniv
 * @package Authorizator\Forms
 */
class PrivilegeForm extends Control
{
    /** @var IAuthorizator */
    private $authorizator;
    /** @var ITranslator|null */
    private $translator;
    /** @var string template path */
    private $templatePath;

    private $state = null;
    /** @var callback method */
    public $onSuccess, $onError;


    /**
     * Forms constructor.
     *
     * @param IAuthorizator    $authorizator
     * @param ITranslator|null $translator
     */
    public function __construct(IAuthorizator $authorizator, ITranslator $translator = null)
    {
        parent::__construct();

        $this->authorizator = $authorizator;
        $this->translator = $translator;

        $this->templatePath = __DIR__ . '/PrivilegeForm.latte';  // default path
    }


    /**
     * Set template path.
     *
     * @param $path
     * @return $this
     */
    public function setTemplatePath($path)
    {
        $this->templatePath = $path;
        return $this;
    }


    /**
     * Create component form.
     *
     * @param $name
     * @return Form
     */
    protected function createComponentForm($name)
    {
        $form = new Form($this, $name);
        $form->setTranslator($this->translator);

        $form->addHidden('id');
        $form->addText('privilege', 'acl-privilegeform-privilege')
            ->setRequired('acl-privilegeform-role-required');

        $form->addSubmit('save', 'acl-privilegeform-save');

        $form->onSuccess[] = function ($form, array $values) {
            try {
                if ($this->authorizator->savePrivilege($values) >= 0) {
                    $this->onSuccess($values);
                }
            } catch (UniqueConstraintViolationException $e) {
                $this->onError($values, $e);
            }
        };
        return $form;
    }


    /**
     * Handle add.
     */
    public function handleAdd()
    {
        $this->state = 'add';
    }


    /**
     * Handle update.
     *
     * @param $id
     */
    public function handleUpdate($id)
    {
        $this->state = 'update';

        $privilege = $this->authorizator->getPrivilege();
        if (isset($privilege[$id])) {
            $this['form']->setDefaults($privilege[$id]);
        }
    }


    /**
     * Handle delete.
     *
     * @param $id
     */
    public function handleDelete($id)
    {
        $privilege = $this->authorizator->getPrivilege();
        if (isset($privilege[$id])) {
            $values = (array) $privilege[$id];

            if ($this->authorizator->savePrivilege(['id' => $id])) {
                $this->onSuccess($values);
            } else {
                $this->onError($values);
            }
        }
    }


    /**
     * Render privilege.
     */
    public function render()
    {
        $template = $this->getTemplate();

        $template->state = $this->state;
        $template->privilege = $this->authorizator->getPrivilege();

        $template->setTranslator($this->translator);
        $template->setFile($this->templatePath);
        $template->render();
    }
}
