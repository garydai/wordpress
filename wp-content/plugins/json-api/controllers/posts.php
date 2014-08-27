<?php
/*
Controller name: Posts
Controller description: Data manipulation methods for posts
*/

class JSON_API_Posts_Controller {

  public function create_post() {
    global $json_api;
/*	return 2;

    if (!current_user_can('edit_posts')) {
      $json_api->error("You need to login with a user that has 'edit_posts' capacity.");
    }
    if (!$json_api->query->nonce) {
      $json_api->error("You must include a 'nonce' value to create posts. Use the `get_nonce` Core API method.");
    }
    $nonce_id = $json_api->get_nonce_id('posts', 'create_post');
    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }


	return 1;
*/
   // nocache_headers();
    $post = new JSON_API_Post();
    $id = $post->create($_REQUEST);
    if (empty($id)) {
      $json_api->error("Could not create post.");
    }
    //$post->update($_REQUEST);
    global $wpdb;
 //  echo  $_REQUEST['user'];

    $query = "insert user_comment set user = '{$_REQUEST['user']}', post_id = {$id}, image_id = {$post->attachments[0]->id}, image_url = '{$post->attachments[0]->url}', showed = 0";
   echo $query;
	 $result = $wpdb->get_results($query);
    return array(
      'post' => $post
    );
  }
  
  public function update_post() {
    global $json_api;
    $post = $json_api->introspector->get_current_post();
    if (empty($post)) {
      $json_api->error("Post not found.");
    }
    if (!current_user_can('edit_post', $post->ID)) {
      $json_api->error("You need to login with a user that has the 'edit_post' capacity for that post.");
    }
    if (!$json_api->query->nonce) {
      $json_api->error("You must include a 'nonce' value to update posts. Use the `get_nonce` Core API method.");
    }
    $nonce_id = $json_api->get_nonce_id('posts', 'update_post');
    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }
    nocache_headers();
    $post = new JSON_API_Post($post);
    $post->update($_REQUEST);

    return array(
      'post' => $post
    );
  }
  
  public function delete_post() {
    global $json_api;
    $post = $json_api->introspector->get_current_post();
    if (empty($post)) {
      $json_api->error("Post not found.");
    }
    if (!current_user_can('edit_post', $post->ID)) {
      $json_api->error("You need to login with a user that has the 'edit_post' capacity for that post.");
    }
    if (!current_user_can('delete_posts')) {
      $json_api->error("You need to login with a user that has the 'delete_posts' capacity.");
    }
    if ($post->post_author != get_current_user_id() && !current_user_can('delete_other_posts')) {
      $json_api->error("You need to login with a user that has the 'delete_other_posts' capacity.");
    }
    if (!$json_api->query->nonce) {
      $json_api->error("You must include a 'nonce' value to update posts. Use the `get_nonce` Core API method.");
    }
    $nonce_id = $json_api->get_nonce_id('posts', 'delete_post');
    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }
    nocache_headers();
    $id = $json_api->query->id;
//   wp_delete_post($post->ID);
#    wp_delete_post($id, True);


              // Get the post with the given ID to check if it still exists
                $postexists = get_post($id);
               
                // If the post exists, delete it
                if ($postexists){
                       
                        // Delete the post
                        wp_delete_post( $id, true );
                       
                        // Let the user know the post has been deleted
                        return array(
                                "Message" => "Post with ID: $id has been deleted"
                        );
               
                // Post with the Id was never found, did it actually exist?
                } else {
                       
                        // Post wasn't found, so return a message
                        return array(
                                "Message" => "Post with ID: $id wasn't found, does it actually exist?"
                        );
                }      



    return array();
  }
  
}

?>
