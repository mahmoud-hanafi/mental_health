<?php

namespace Drupal\mental_health\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;
use Drupal\Core\Url;
use Drupal\profile\Entity\Profile;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use \Symfony\Component\HttpFoundation\RedirectResponse;


class Confirm extends FormBase{
    /**
     * (@inheritdoc)
     */
    public function getFormId()
    {
        return 'confirm';
    }

    /**
     * (@inheritdoc)
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $profile_id = $_GET['id'];
        $profile = Profile::load($profile_id);
        //kint($profile);
        $uri = $profile->field_picture->target_id;
        $full_name = $profile->field_full_name->value;
        $hospital_id = $profile->field_hospital->target_id;
        
       
        $hospital = \Drupal\node\Entity\Node::load($hospital_id);
        $hospital_name = $hospital->title->value;
        $hospital_address = strip_tags($hospital->field_hospital_address->value);

        $file = File::load($uri);
        $image_uri = $file->getFileUri();
        $image_uri = explode('//',$image_uri);
        $img = "/mental/web/sites/default/files/".$image_uri[1];

        $form['description'] = array(
            '#markup' => '<div class="container">
            <div class="booking-confirmation">
                <div class="left-section">
                    <div class="doctor-img">
                         <img src="'.$img.'" alt="">
                         <h3 class="full-name">Full Name</h3>
                    </div>
                    <div class="doctor-name">
                         <p class="value">'.$full_name.'</p>
                    </div>
                </div>
                <div class="right-section">
                     <div class="info-item">
                        <div class="label">Booking Date</div>
                        <span>Sunday</span>
                            <a href="/mental/web/zoom-meeting">2-2:30</a>
                            <a href="/mental/web/zoom-meeting">2:30-3</a>
                            <a href="/mental/web/zoom-meeting">3:30-4</a>
                            <a href="/mental/web/zoom-meeting">4:30-5</a>
                        <span>Wednesday</span>
                        <a href="/mental/web/zoom-meeting">2-2:30</a>
                        <a href="/mental/web/zoom-meeting">2:30-3</a>
                        
                     </div>
                     <div class="info-item">
                        <p class="label">Hospital</p>
                        <p class="value">'.$hospital_name.'</p>
                     </div>
                     <div class="info-item">
                         <p class="label">Hospital Address</p>
                         <p class="value">'.$hospital_address.'</p>
                     </div>
                </div>
            </div>
         </div>',
        );
        
        // $form['#attributes']['class'][] = 'booking-date-bottom';


        //   $form['actions']['#type'] = 'actions';
      
      
        //   $form['actions']['submit'] = array(
        //     '#type' => 'submit',
        //     '#value' => $this->t('Confirm'),
        //     '#button_type' => 'info',
        //   );
        return $form;
    }

    /**
     * (@inheritdoc)
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        # code...
    }

    /**
     * (@inheritdoc)
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        //$time = $form_state->getValue('time');
        //var_dump($_POST);
        // print $time;
        // exit;
        
        # code...
        $redirect = new RedirectResponse(Url::fromUri("internal:/zoom-meeting")->toString()); 
        $redirect->send();
    }
}

