<?php
/**
 * @file
 * Return json format of the basic page nodes.
 * Reference: drupal.org,https://drupal.stackexchange.com/questions/191419/drupal-8-node-serialization-to-json?answertab=active#tab-top
 * Enable serialization module.
 */


namespace Drupal\node_page_json\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for node page json module route.
 */
class PageJsonController extends ControllerBase {

   /**
   * Render node as json
   *
   * @param $api_key 
   * @param $nid
   *   The feed source URL.
   * @return json response of the node
   */
	public function getPageJson($api_key, $nid) {
        //Get the api key configured in the site configuration page.
		$site_api_key = \Drupal::configFactory()->getEditable('system.site')->get('siteapikey');
		if($site_api_key == $api_key) {
			$node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);		
		    if(isset($node)) {
		      	$type_name = $node->type->entity->label();
                if($type_name == 'Basic page') {
					$serializer = \Drupal::service('serializer');$serializer = \Drupal::service('serializer');
					$data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
					return new JsonResponse(json_decode($data));
				}
				else {
					return new JsonResponse('Nid is incorrect');
			}
           }
			else {
				 return new JsonResponse('Node does not exists');
			}

		}
		else {
			 return new JsonResponse('Api key is incorrect');
		}
	}
}
