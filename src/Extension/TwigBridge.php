<?php
namespace NetoJose\Bootstrap4Forms\Extension;

use \NetoJose\Bootstrap4Forms\FormService;
use TwigBridge\Extension\Laravel\Form;

/**
 * Access Laravels bootstrap themed form builder in Twig templates
 *
 * Add 'NetoJose\Bootstrap4Forms\Extension\TwigBridge' to TwigBridge config at extension section
 */
class TwigBridge extends Form
{
    /**
     * @var FormService
     */
    protected $form;

    /**
     * Create a new form extension
     *
     * @param FormService $form
     */
    public function __construct(FormService $form)
    {
        $this->form = $form;
    }
}