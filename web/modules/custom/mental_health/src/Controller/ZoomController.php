<?php

namespace Drupal\mental_health\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Drupal\core\Url;
use \Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Implement integration with zoom api.
 */
class ZoomController extends ControllerBase
{

  private $client;
  private $zoomService;

  public function __construct()
  {
    $this->client = \Drupal::service('zoomapi.client');
  }

  public function createMeeting()
  {
    // Make the POST request to the zoom api.
    $zoom_request = $this->client->request(
      'post',
      '/users/' . $this->getUserId() . '/meetings',
      [],
      $this->getMeetingData()
    );
    $meeting_join_url= $zoom_request['join_url'];
    $link = Url::fromUri($meeting_join_url)->toString();
    // $response = new RedirectResponse($redirect_to);
    // $response->send();

    return [
      '#theme' => 'confirm_zoom',
      '#meeting_link' => $link, 
    ];
  }

  public function getUserId()
  {
    // https://api.zoom.us/v2/users/{email}
    $response = $this->client->request('GET', '/users/me');
    $user_id = $response['id'];
    return $user_id;
  }

  private function getMeetingData($meetingConfig = [])
  {
    //var_dump($this->toZoomTimeFormat($meetingConfig['start_time']));
    //die;
    return [
      'topic'      => $meetingConfig['topic'] ?? 'General Talk',
      'type'      => $meetingConfig['type'] ?? 2,
      'start_time'  => $this->toZoomTimeFormat($meetingConfig['start_time']),
      'duration'    => $meetingConfig['duration']   ?? 30,
      'password'    => $meetingConfig['password']   ?? mt_rand(),
      'timezone'    => $this->getTimeZone(), //'Africa/Cairo'
      'agenda'    => 'Doctor Meeting',
      'settings'    => [
        'host_video'      => false,
        'participant_video'    => true,
        'cn_meeting'      => false,
        'in_meeting'      => false,
        'join_before_host'    => true,
        'mute_upon_entry'    => true,
        'watermark'        => false,
        'use_pmi'        => false,
        'approval_type'      => 1,
        'registration_type'    => 1,
        'audio'          => 'voip',
        'auto_recording'    => 'none',
        'waiting_room'      => false
      ]
    ];
  }

  private function toZoomTimeFormat($dateTime)
  {
    $time = '2021-10-31T12:00';
    $date = new \DateTime($time);
    return $date->format('Y-m-dTH:i:s');
  }

  private function getTimeZone()
  {

    $config = \Drupal::config('system.date');
    $timezone = $config->get('timezone.default');
    return $timezone;
  }
}