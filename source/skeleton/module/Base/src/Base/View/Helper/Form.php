<?php

namespace Docapost\Base\View\Helper;

use Zend\Form\View\Helper\Form as ZendForm;
use Zend\Form\FormInterface;

class Form extends ZendForm
{
    const ELEMENT_SELECT   = 'select';
    const ELEMENT_TEXT     = 'text';
    const ELEMENT_HIDDEN   = 'hidden';
    const ELEMENT_TEXTAREA = 'textarea';
    const ELEMENT_CHECKBOX = 'checkbox';
    const ELEMENT_OPTION   = 'option';
    const ELEMENT_BUTTON   = 'button';
    const ELEMENT_SUBMIT   = 'submit';
    const ELEMENT_PASSWORD = 'password';
    const ELEMENT_SELECTED = 'selected="selected"';
    const ELEMENT_CHECKED  = 'checked';
    const DEFAULT_SUBMIT   = 'Enregistrer';
    const DIV_CLOSE_TWICE  = '</div></div>';

    public function __invoke(FormInterface $form = null)
    {
        if (!$form) {
            return $this;
        }

        return $this->render($form);
    }

    public function renderMessages($form)
    {
        $output        = '';
        $errorMessages = array();

        foreach ($form as $em){
            if($em->getMessages()){
                foreach ($em->getMessages() as $key => $value){
                    $errorMessages[] = $this->getView()->translate($em->getMessages()[$key]);
                }
            }
        }

        if(count($errorMessages)>0){
            $output = '<div id="messages" class="alert alert-dismissible alert-danger">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                    . '<ul>';

            foreach ($errorMessages as $message){
                $output .= '<li>' . $message . '</li>';
            }

            $output .= '</ul>'
                    . '</div>';
        }

        return $output;
    }

    public function render(FormInterface $form = null)
    {
    	if ($form instanceof FormInterface) {
            $form->setAttribute('class', 'form-horizontal');
    	    $renderer     = $this->getView();
            $openTag      = $this->openTag($form);
            $output       = "<div class='row'><div class='col-lg-6'>";
            $ajaxClass    = '';
            $srOnly       = false;

            foreach ($form as $em) {
                if ($em instanceof \Zend\Form\Fieldset) {
                    $fieldsetName = $em->getAttributes()['name'];
                    foreach ($em->getElements() as $element) {
                        $em    = $element;
                        $type  = $em->getAttribute('type');
                        $name  = $em->getAttribute('name') ? $fieldsetName."[".$em->getAttribute('name')."]" : '';
                        $label = $em->getAttribute('label') ? $renderer->translate($em->getAttribute('label')) : '';
                        $id    = $em->getAttribute('id') ? $em->getAttribute('id') : $em->getAttribute('name');

                        if (is_object($em->getValue()) && method_exists($em->getValue(), 'getId')) {
                            $value = $em->getValue()->getId();
                        } else {
                            $value = ($em->getValue() === false) ? 0 : $em->getValue();
                        }

                        if (preg_match("/[\s]*(param_)/i", $name)) {
                            $ajaxClass = 'ajaxFill';
                        }

                        $placeholder = $em->getAttribute('placeholder') ? $renderer->translate($em->getAttribute('placeholder')) : '';
                        $disabled    = $em->getAttribute('disabled') ? 'disabled=' . $em->getAttribute('disabled') : '';
                        $class       = ($em->getAttribute('class') ? $em->getAttribute('class').' ' : '') . 'form-control';
                        $classLabel  = isset($em->getOptions()['label_attributes']['class']) ? $em->getOptions()['label_attributes']['class'].' ' : '';

                        if (trim($classLabel) == 'sr-only') {
                            $elementHeader = "<div class='form-group $ajaxClass'><label for='$name' class='$classLabel control-label'>$label</label><div>";
                            $srOnly        = true;
                        } else {
                            $elementHeader = "<div class='form-group $ajaxClass'><label for='$name' class='$classLabel control-label col-sm-3'>$label</label><div class='col-sm-9'>";
                        }

                        $checkboxHeader = '<div class="form-group"><div class="col-sm-offset-3 col-sm-9"><div class="checkbox"><label>';
                        $checkboxFooter = '</label></div></div></div>';
                        $next			= $em->getAttribute('next') ? ' <span class="fa fa-chevron-right"></span>' : '';
                        $checked        = '';

                        if ($type == self::ELEMENT_SELECT) {
                            $output .= $elementHeader;
                            $output .= "<".self::ELEMENT_SELECT . " value='$value' class='$class' name='$name' id='$id' $disabled>";
                            if ($em->getOption('empty_option')) {
                                $output .= "<" . self::ELEMENT_OPTION . " value=''>". $renderer->translate($em->getOption('empty_option')) . "</" . self::ELEMENT_OPTION . ">";
                            }
                            foreach ($em->getValueOptions() as $key => $option) {
                                $selected = (!is_object($value) && $key == $value && !is_null($value)) ? self::ELEMENT_SELECTED : '';
                                $output  .= "<" . self::ELEMENT_OPTION . " value='$key' $selected>" . $renderer->translate($option) . "</" . self::ELEMENT_OPTION . ">";
                                unset($selected);
                            }
                            $output .= "</" . self::ELEMENT_SELECT . ">";
                            $output .= self::DIV_CLOSE_TWICE;
                        } elseif ($type == self::ELEMENT_TEXT) {
                            $output    .= $elementHeader;
                            $inputValue = ($value instanceof \DateTime) ? $this->getView()->dateFormat($value, 3, 2) : $value;
                            $output .= "<input type='".self::ELEMENT_TEXT."' value='$inputValue' class='$class' name='$name' id='$id' placeholder=\"$placeholder\" $disabled>";
                            $output .= self::DIV_CLOSE_TWICE;
                        } elseif ($type == self::ELEMENT_PASSWORD) {
                            $output .= $elementHeader;
                            $output .= "<input type='".self::ELEMENT_PASSWORD."' value='$value' class='$class' name='$name' id='$id' placeholder=\"$placeholder\" $disabled>";
                            $output .= self::DIV_CLOSE_TWICE;
                        } elseif ($type == self::ELEMENT_HIDDEN) {
                            $output .= "<input type='".self::ELEMENT_HIDDEN."' value='$value' name='$name' id='$id'>";
                        } elseif ($type == self::ELEMENT_TEXTAREA) {
                            $output .= $elementHeader;
                            $output .= "<".self::ELEMENT_TEXTAREA." class='$class' name='$name' id='$id' placeholder=\"$placeholder\" $disabled>$value</".self::ELEMENT_TEXTAREA.">";
                            $output .= self::DIV_CLOSE_TWICE;
                        } elseif ($type == self::ELEMENT_CHECKBOX) {
                            if (!empty($value)) {
                                $checked = self::ELEMENT_CHECKED;
                            }
                            $class   = $em->getAttribute('class');
                            $output .= "$checkboxHeader<input type='hidden' name='".$em->getAttribute('name')."' value='".$em->getUncheckedValue()."'>"
                                    .  "<input type='".self::ELEMENT_CHECKBOX."' name='".$em->getAttribute('name')."' value='".$em->getCheckedValue()."' class='$class' $checked><span class='$class'>$label</span>"
                                    .  $checkboxFooter;

                        } elseif ($type == self::ELEMENT_BUTTON) {
                            $labelButton = ($value != '') ? $value : $em->getLabel();
                            $output .= "<div class='col-lg-1 col-md-1 col-xs-12 alignSecondButton'>"
                                    .  "<button name='".$name."' id='".$id."' type='".self::ELEMENT_BUTTON."' value='".$renderer->translate($labelButton)."' class='btn btn-primary ".$class."' ".$disabled.">"
                                    .  $renderer->translate($labelButton)." <span class='fa fa-chevron-right'></span></button></div>";
                        } elseif ($type == self::ELEMENT_SUBMIT) {
                            $labelButton = ($value != '') ? $value : $em->getLabel();
                            if (true === $srOnly) {
                                $output .= "<div class='form-group'>";
                                $output .= "<button type='".self::ELEMENT_SUBMIT."' name='$name' class='btn-primary btn-block btn-lg btn' title=' ' value=''>".$renderer->translate($labelButton)."</button></div>";
                            } else {
                                $classPrimary = ($name == "cancel") ? 'btn-default' : 'btn-primary';
                                $output .= "<div id='feedAjax'></div><div class='pull-right spaceButton'>"
                                        .  "<button name='$name' id='$id' type='".self::ELEMENT_SUBMIT."' value='".$renderer->translate($labelButton)."' class='btn $classPrimary $class' $disabled>"
                                        .  $renderer->translate($labelButton).$next."</button></div>";
                            }
                        }
                    }
                } else {
                    $groupClass = 'form-group';
                    $dataToggle = '';
                    $type  = $em->getAttribute('type');
                    $name  = $em->getAttribute('name');
                    $title = $renderer->translate($em->getAttribute('title'));
                    $label = $renderer->translate($em->getOption('label'));
                    $id    = $em->getAttribute('id') ? $em->getAttribute('id') : $em->getAttribute('name'); 

                    if (is_object($em->getValue()) && method_exists($em->getValue(), 'getId')) {
                        $value = $em->getValue()->getId();
                    } else {
                        $value = ($em->getValue() === false) ? 0 : $em->getValue();
                    }

                    if (preg_match("/[\s]*(param_)/i", $name)) {
                        $ajaxClass = 'ajaxFill';
                    }

                    $placeholder = $em->getAttribute('placeholder') ? $renderer->translate($em->getAttribute('placeholder')) : '';
                    $disabled    = $em->getAttribute('disabled')    ? 'disabled=' .$em->getAttribute('disabled') : '';
                    $class       = ($em->getAttribute('class')      ? $em->getAttribute('class').' ' : '') . 'form-control';
                    $classLabel  = isset($em->getOptions()['label_attributes']['class']) ? $em->getOptions()['label_attributes']['class'].' ' : '';

                    //  Error Messages
                    if ($em->getMessages()) {
                        $groupClass .= ' has-error has-feedback';
                        $messages = [];
                        foreach ($em->getMessages() as $message) {
                            $messages[] = $renderer->translate($message);
                        }
                        $title       = implode('</ br>', $messages);
                        $dataToggle  = ' data-html="true" data-toggle="tooltip"';
                    }

                    if (trim($classLabel) == 'sr-only') {
                        $elementHeader  = "<div class='$groupClass $ajaxClass'><label for='$name' class='$classLabel control-label'>$label</label><div>";
                        $srOnly         = true;
                    } else {
                        $elementHeader  = "<div class='$groupClass $ajaxClass'><label for='".$name."' class='$classLabel control-label col-sm-3'>$label</label><div class='col-sm-9'>";
                    }

                    $checkboxHeader = '<div class="form-group"><div class="col-sm-offset-3 col-sm-9"><div class="checkbox"><label>';
                    $checkboxFooter = '</label></div></div></div>';
                    $next			= $em->getAttribute('next') ? ' <span class="fa fa-chevron-right"></span>' : '';
                    $checked        = '';

                    if ($type == self::ELEMENT_SELECT) {
                        $output .= "$elementHeader<".self::ELEMENT_SELECT." value='$value' class='$class' name='$name' id='$id' title=\"$title\" $disabled $dataToggle>";
                        if (isset($em->getOptions()['empty_option'])) {
                            $output .= "<".self::ELEMENT_OPTION." value=''>".$renderer->translate($em->getOption('empty_option'))."</".self::ELEMENT_OPTION.">";
                        }
                        foreach ($em->getValueOptions() as $key => $option) {
                            $selected = (!is_object($value) && $key == $value && !is_null($value)) ? self::ELEMENT_SELECTED : '';
                            $output  .= "<".self::ELEMENT_OPTION." value='$key' $selected>".$renderer->translate($option)."</".self::ELEMENT_OPTION.">";
                            unset($selected);
                        }
                        $output .= "</".self::ELEMENT_SELECT.">".self::DIV_CLOSE_TWICE;
                    } elseif ($type == self::ELEMENT_TEXT) {
                        $inputValue = ($value instanceof \DateTime) ? $this->getView()->dateFormat($value, 3, 2) : $value;
                        $output .= $elementHeader
                                .  "<input type='".self::ELEMENT_TEXT."' value='$inputValue' class='$class' name='$name' id='$id' title=\"$title\" placeholder=\"$placeholder\" $disabled $dataToggle>"
                                .  self::DIV_CLOSE_TWICE;
                    } elseif ($type == self::ELEMENT_PASSWORD) {
                        $output .= $elementHeader
                                .  "<input type='".self::ELEMENT_PASSWORD."' value='$value' class='$class' name='$name' id='$id' title=\"$title\" placeholder=\"$placeholder\" $disabled $dataToggle>"
                                .  self::DIV_CLOSE_TWICE;
                    } elseif ($type == self::ELEMENT_HIDDEN) {
                        $output .= "<input type='".self::ELEMENT_HIDDEN."' value='$value' name='$name' id='$id'>";
                    } elseif ($type == self::ELEMENT_TEXTAREA) {
                        $output .= $elementHeader
                                .  "<".self::ELEMENT_TEXTAREA." class='$class' name='$name' id='$id' title=\"$title\"placeholder=\"$placeholder\" $disabled>$value</".self::ELEMENT_TEXTAREA.">"
                                .  self::DIV_CLOSE_TWICE;
                    } elseif ($type == self::ELEMENT_CHECKBOX) {
                        if (!empty($value)) {
                            $checked = self::ELEMENT_CHECKED;
                        }
                        $class   = isset($em->getAttributes()['class']) ? $em->getAttributes()['class'] : '';
                        $output .= $checkboxHeader
                                .  "<input type='hidden' name='".$em->getAttribute('name')."' value='".$em->getUncheckedValue()."'>"
                                .  "<input type='".self::ELEMENT_CHECKBOX."' name='".$em->getAttribute('name')."' value='".$em->getCheckedValue()."' class='$class' title=\"$title\" $checked $dataToggle>"
                                .  "<span class='$class'>$label</span>$checkboxFooter";
                    } elseif ($type == self::ELEMENT_SUBMIT || $type == self::ELEMENT_BUTTON) {
                        $labelButton  = ($value != '') ? $value : $em->getLabel();

                        if (true === $srOnly) {
                            $output .= "<div class='form-group'><button type='$type' name='$name' class='btn-primary btn-block btn-lg btn' title=\"$title\" value=''>".$renderer->translate($labelButton)."</button></div>";
                        } else {
                            $classPrimary = ($name == "cancel") ? 'btn-default' : 'btn-primary';
                            $output .= "<div id='feedAjax'></div><div class='pull-right spaceButton'>"
                                    .  "<button name='$name' id='$id' type='$type' value='".$renderer->translate($labelButton)."' class='btn $classPrimary $class' title=\"$title\" $disabled $dataToggle>"
                                    .  $renderer->translate($labelButton).$next."</button></div>";
                        }
                    }
                }
            }

            $closeTag  = "</div></div>" . $this->closeTag();

            return $openTag . $output . $closeTag;
        }

        return '';
    }
}