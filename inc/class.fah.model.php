<?php
class FAH_Model {
	protected $post_type;
	protected $post;
	protected $skip_properties = array();
	public function __construct($id = null)
	{
		$post = get_post($id);	
		if($post && $post->post_type == $this->post_type) 
		{
			$this->id = $post->ID;
			$this->post = $post;
			$metadata = get_metadata('post', $this->id);
			foreach ($metadata as $key => $value) 
			{
				if(property_exists($this, $key)) 
				{
					$this->$key = $value;
				}
			}
		}
	}
	public function set_data($data = array())
	{
		foreach ($data as $key => $value) 
		{
			if(property_exists($this, $key)) 
			{
				$this->$key = $value;
			}
		}
		
	}
	
	protected function after_setting_data() {}
	
	public function save($data = array())
	{
		$data['post_type'] = $this->post_type;
		if($this->id) {
			$data['ID'] = $this->id;
			$post_id = wp_update_post( $data );
		} else {
			$post_id = wp_insert_post($data);
			$this->id = $post_id;
		}
		$all_data = get_object_vars($this);
		foreach ($all_data as $key => $value) {
			if(in_array($key, $this->skip_properties)) continue;
			update_post_meta($post_id, $key, $value);
		}
		return $post_id;
	}
}