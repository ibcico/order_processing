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
			$entry_id = $context['entry']->get('id');
			
			$articles = $_POST['fields']['article'];
			$quantity = $_POST['fields']['count'];
			
			if(count($articles) > 0 and count($quantity) > 0){
				$i = 0;
				foreach($articles as $article) { 
					$entrymanager = new EntryManager($this);
					$entry = $entrymanager->create();
					$entry->set('section_id', 17);
					$entry->setData(59, array('handle' => $quantity[$i], 'value' => $quantity[$i], 'value_formatted' => $quantity[$i], 'word_count' => 1));
			    	$entry->setData(60, array('relation_id' => $article));
					$entry->commit();
					$id = $entry->get('id');
					$i++;
					Symphony::Database()->query("INSERT INTO tbl_entries_data_62 (id, entry_id, relation_id) VALUES (NULL, '$entry_id', '$id')");
				}
				$session_id = session_id();
				$order_id = Symphony::Database()->fetchVar('entry_id', 0, "SELECT entry_id FROM tbl_entries_data_26 WHERE value = '$session_id'");
				if($order_id){
					$entrymanager = new EntryManager($this);
					$entrymanager->delete($order_id);
				}
				//Symphony::Database()->query("DELETE FROM tbl_entries_data_28 WHERE id = '$order_id'");
			}
			
			/*	
			$entrymanager = new EntryManager($this);
			$entry = $entrymanager->create();
			$entry->set('section_id', 17);
	    	$entry->setData(26, array('handle' => $session_id, 'value'=> $session_id, 'value_formatted' => $session_id, 'word_count' => '1'));
			$entry->commit();
			$id = $entry->get('id');
			
			
			
			$id = Symphony::Database()->fetchVar('entry_id', 0, "SELECT entry_id FROM tbl_entries_data_26 WHERE value = '$session_id'");
			
			if(!$id){
				
			}
			
			$subsection = Symphony::Database()->fetchVar('id', 0, "SELECT id FROM tbl_entries_data_28 WHERE entry_id = '$id' AND relation_id = '$field_id'");
			
			if(!$subsection){
				Symphony::Database()->query("INSERT INTO tbl_entries_data_28 (id, entry_id, relation_id, count) VALUES (NULL, '$id', '$field_id', '$count')");
			}else{
				Symphony::Database()->query("UPDATE tbl_entries_data_28 SET count = count + $count WHERE entry_id = '$id' AND relation_id = '$field_id'");
			}
			

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
			*/
		}
	}