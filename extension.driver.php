<?php
	Class extension_order_processing extends Extension
	{
		/*-------------------------------------------------------------------------
			Extension definition
		-------------------------------------------------------------------------*/
		
	public $ENT;
	
	public $count = 0;
	
	public function about()
		{
			return array(
				'name' => 'Order Processing',
				'version'	=> '0.0.1',
				'author'	=> array('name' => 'Igor Bogadanov',
									'website' => 'http://ibcico.com/',
									'email' => 'i.bogdanov@ibcico.com'),
				'release-date' => '2011-18-01',
			);
		}
		
		
		
	public function getSubscribedDelegates()
		{
			return array(
			array(
					'page'		=> '/blueprints/events/edit/',
					'delegate'	=> 'AppendEventFilter',
					'callback'	=> 'add_filter_to_event_editor'
				),				
				array(
					'page'		=> '/blueprints/events/new/',
					'delegate'	=> 'AppendEventFilter',
					'callback'	=> 'add_filter_to_event_editor'
				),
				array(
					'page'		=> '/frontend/',
					'delegate'	=> 'EventPostSaveFilter',
					'callback'	=> 'collect_data'
				),
			);
		}
				
		
	public function add_filter_to_event_editor(&$context)
		{
			$context['options'][] = array('order-processing', @in_array('order-processing', $context['selected']) ,'Order Processing');
		}
	
	public function collect_data($context)
		{
			$position = $this->count;
			$this->ENT[$this->count] = $context['entry']->get('id');
			$order_item_id = $context['entry']->get('id');
			
			
			$_POST['fields']['tovary'][$position] = $order_item_id;
			
			$_POST['fields']['imya'] = $_POST['order']['imya'];
			$_POST['fields']['email'] = $_POST['order']['email'];
			$_POST['fields']['telefon'] = $_POST['order']['telefon'];
			$_POST['fields']['kompaniya'] = $_POST['order']['kompaniya'];
			$_POST['fields']['inn'] = $_POST['order']['inn'];
			$_POST['fields']['kpp'] = $_POST['order']['kpp'];
			$_POST['fields']['soobschenie'] = $_POST['order']['soobschenie'];
			$_POST['fields']['chastnoe-litso'] = $_POST['order']['chastnoe-litso'];
			

			$_POST['action']['order-event-2'] = 'true';
			
			$this->count += 1;
		}
	}