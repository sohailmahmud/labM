<?php

	require_once "../config/config.php";


	/**
	* 
	*/
	class display
	{
		
		function display_room()
		{
			global $conn;
			$sql = $conn->prepare("SELECT * FROM room WHERE rm_status = ?");
			$sql->execute(array(1));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				foreach ($fetch as $key => $value) {
					$myname = $value['rm_name'];

					
					$button1 = 	'<div class="btn-group">
									<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action <span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li><a href="javascript:;" class="edit-room" ><i class="fa fa-edit"></i> Edit</a></li>
										<li><a href="room_info?name='.$myname.'&id='.$value["id"].'"><i class="fa fa-search"></i> View Items</a></li>
									</ul>
								</div>';

					$button2 = 	'<div class="btn-group">
									<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action <span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li><a href="javascript:;" class="edit-room" ><i class="fa fa-edit"></i> Edit</a></li>
									</ul>
								</div>';

					// <li><a href="room_info?name='.$myname.'&id='.$value["id"].'"><i class="fa fa-search"></i> View equipments</a></li>

					$button =  $button1;

					$data['data'][] = array(ucwords($value['rm_name']),$button,$value['id']);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}


		public function display_member()
		{
			global $conn; 
			$sql = $conn->prepare("SELECT * FROM member");
			$sql->execute();
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				foreach ($fetch as $key => $value) {
					$status = ($value['m_status'] == 1) ? '<a href="javascript:;" class="deactivate-member" ><i class="fa fa-remove"></i> deactivate</a>' : '<a href="javascript:;" class="activate-member" ><i class="fa fa-remove"></i> activate</a>' ;
					$button = 	'<div class="btn-group">
									<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action <span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li><a href="javascript:;" class="edit-member" ><i class="fa fa-edit"></i> Edit</a></li>
										<li>'.$status.'</li>
										<li><a href="member_profile?id='.$value['id'].'&name='.$value['m_fname'].' '.$value['m_lname'].'"><i class="fa fa-user"></i> Borrower Profile</a></li>
									</ul>
								</div>';

					$data['data'][] = array($value['m_school_id'],$value['m_fname'].' '.$value['m_lname'],$value['m_gender'],$value['m_contact'],$value['m_department'],$value['m_year_section'],$value['m_type'],$button,$value['m_fname'],$value['m_lname'],$value['id']);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}


		public function display_user()
		{
			global $conn; 
			$sql = $conn->prepare("SELECT * FROM user");
			$sql->execute();
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				foreach ($fetch as $key => $value) {
					$status = ($value['status'] == 1) ? '<a href="javascript:;" class="deactivate-user" ><i class="fa fa-remove"></i> deactivate</a>' : '<a href="javascript:;" class="activate-user" ><i class="fa fa-remove"></i> activate</a>' ;
					$button = 	'<div class="btn-group">
									<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action <span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li><a href="javascript:;" class="edit-user" ><i class="fa fa-edit"></i> Edit</a></li>
										<li>'.$status.'</li>
										<li><a href="javascript:;" class="edit-upass" ><i class="fa fa-lock"></i> Change Password</a></li>
									</ul>
								</div>';
					$type = ($value['type'] == 1) ? 'Administrator' : 'Staff/ Student Assistance';
					$data['data'][] = array($value['name'],$type,$value['username'],$button,$value['id'],$value['password']);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}


		public function display_roomtype()
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM room WHERE rm_name = ?');
			$sql->execute(array('room 310'));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				
				foreach ($fetch as $key => $value) {
					$data[] = array($value['id'],ucwords($value['rm_name']));
				}
				echo json_encode($data);

			}else{
				echo "0";
			}
		}

		public function display_roomtype1($id)
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM room WHERE rm_status = ? AND id != ? ORDER BY rm_name ASC');
			$sql->execute(array(1,$id));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				
				foreach ($fetch as $key => $value) {
					$data[] = array($value['id'],ucwords($value['rm_name']));
				}
				echo json_encode($data);

			}else{
				echo "0";
			}
		}

		public function display_equipment()
		{
			global $conn;

			// $sql = $conn->prepare('SELECT *, item.id as e_id FROM item 
			// 					   LEFT JOIN item_stock ON item_stock.item_id = item.id
			// 					   WHERE item_stock.item_status = ? OR item_stock.item_status = ?');

			$sql = $conn->prepare('SELECT *, item_stock.id as e_id FROM item_stock
									LEFT JOIN item ON item.id = item_stock.item_id
									WHERE item_stock.item_status = ? OR item_stock.item_status = ?');
			$sql->execute(array(1,2));
			$row = $sql->rowCount();
			$fetch = $sql->fetchAll();

			if($row > 0){

				foreach ($fetch as $key => $value) {
					$str_status = ($value['item_status'] == 1) ? 'NEW' : 'OLD' ;
					$button = 	'<div class="btn-group">
									
										<a href="items_info?item='.$value['e_id'].'&1Qke#%Rasd2#a1Qasd" class="btn btn-primary"><i class="fa fa-search"></i> More info</a>
									
								</div>';

					$photo = ($value['i_photo'] == "") ? "../assets/noimagefound.jpg" : "../uploads/" . $value['i_photo'];
					$data['data'][] = array('<img src="'.$photo.'" class="img-responsive" />',$value['i_model'],$value['i_category'],$value['i_description'],$value['item_rawstock'],$value['items_stock'],$str_status,$button,$value['i_brand']);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}

			// $sql = $conn->prepare('SELECT *, room.id as rm_id, equipment.id as e_id FROM equipment
			// 						LEFT JOIN item_stock ON item_stock.item_id = equipment.id
			// 						LEFT JOIN room ON room.id = equipment.room_id');
			// $sql->execute();
			// $count = $sql->rowCount();
			// $fetch = $sql->fetchAll();
			// if($count > 0){
				
			// 	foreach ($fetch as $key => $value) {
			// 		$button = 	'<div class="btn-group">
			// 						<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			// 							Action <span class="caret"></span>
			// 						</button>
			// 						<ul class="dropdown-menu">
			// 							<li><a href="javascript:;" class="equip_details"><i class="fa fa-eye"></i> Details</a></li>
			// 							<li><a href="items_info?item='.$value['e_id'].'&1Qke#%Rasd2#a1Qasd"><i class="fa fa-search"></i> More info</a></li>
			// 						</ul>
			// 					</div>';
			// 			// <li><a href="javascript:;" class="equip_moveroom"><i class="fa fa-arrows"></i> Move room</a></li>
			// 			// $status = ($value['e_status'] == 1) ? 'New': 'Old';

			// 		$data['data'][] = array($value['e_deviceid'],$value['e_description'],$value['e_stock'],ucwords($value['rm_name']),$value['e_status'],$button,ucwords($value['e_category']),$value['e_brand'],$value['e_type'],$value['e_id'],$value['rm_id'],$value['items_stock'],$value['e_model']);
			// 	}
			// 	echo json_encode($data);

			// }else{
			// 	$data['data'] = array();
			// 	echo json_encode($data);
			// }
		}

		public function display_roominfo($id,$name)
		{
			global $conn;

			// $sql = $conn->prepare(' SELECT * FROM equipment
			// 						-- LEFT JOIN room_equipment ON room_equipment.equipment_id = equipment.id
			// 						WHERE room_id = ?');

			if($name == 'room 310'){

				$sql = $conn->prepare('SELECT *, item_stock.id as itemID FROM item_stock 
										LEFT JOIN item ON item.id = item_stock.item_id
										LEFT JOIN room ON room.id = item_stock.room_id 
										WHERE item_stock.room_id = ? AND item_stock.items_stock != ?');
				$sql->execute(array($id,0));
				$count = $sql->rowCount();
				$fetch = $sql->fetchAll();
					if($count > 0){
						
						foreach ($fetch as $key => $value) {


							$button = 	'<div class="btn-group">
											<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Action <span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li><a href="javascript:;" class="transfer" data-id="'.$value['itemID'].'"><i class="fa fa-forward"></i> Transfer</a></li>
											</ul>
										</div>';

							$item_status = ($value['item_status'] == 1) ? 'New' : 'Old';

						
							$data['data'][] = array($value['i_model'],ucwords($value['i_category']),ucwords($value['i_brand']),$value['i_description'],$value['items_stock'],ucwords($value['i_type']),$item_status,$button);
						}
						echo json_encode($data);

					}else{
						$data['data'] = array();
						echo json_encode($data);
					}
			}else{
				$sql = $conn->prepare('SELECT *, item_transfer.id as itemID FROM item_transfer
										LEFT JOIN item_stock ON item_stock.id = item_transfer.t_stockID
										LEFT JOIN item ON item.id = item_stock.item_id
										WHERE item_transfer.t_roomID = ? AND item_transfer.t_status = ?');
				$sql->execute(array($id,1));
				$row = $sql->rowCount();
				$fetch = $sql->fetchAll();
					if($row > 0){
						foreach ($fetch as $key => $value) {
							$button = "<button class='btn btn-primary btn_return' data-id=".$value['itemID'].">Return to 310</button>";
							$data['data'][] = array($value['i_model'],ucwords($value['i_category']),ucwords($value['i_brand']),$value['i_description'],$value['t_quantity'],ucwords($value['i_type']),'Transferred',$button);
						}
						echo json_encode($data);
					}else{
						$data['data'] = array();
						echo json_encode($data);
					}
			}
		}


		public function display_newtransaction()
		{
			global $conn;

			$sql = $conn->prepare("SELECT * FROM equipment
									LEFT JOIN room ON room.id = equipment.room_id
									WHERE equipment.e_stockleft != ?");
			$sql->execute(array(0));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();

			if($count > 0){
				foreach ($fetch as $key => $value) {
					$input = "<div class='form-group'><input type='checkbox' value='".$value['id']."' name='items[]'></div>";
					$data['data'][] = array($input, $value['e_deviceid'],ucwords($value['e_category']),ucwords($value['e_brand']),ucwords($value['rm_name']),$value['e_stockleft']);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}

		}


		public function display_memberselect()
		{
			global $conn;

			$sql = $conn->prepare("SELECT * FROM member WHERE m_status = ? ORDER BY m_fname ASC");
			$sql->execute(array(1));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				foreach ($fetch as $key => $value) {
					$data[] = array($value['m_fname'].' '.$value['m_lname'],$value['m_school_id'],$value['id']);	
				}
				echo json_encode($data);
			}else{
				echo "0";
			}
		}


		public function display_equipmentinfo($id)
		{
			global $conn;

			$sql = $conn->prepare("SELECT * FROM item_stock 
								   LEFT JOIN item ON item.id = item_stock.item_id
								   WHERE item_stock.id = ?");
			$sql->execute(array($id));
			$fetch = $sql->fetchAll();
			$count = $sql->rowCount();
			if($count > 0){
				foreach ($fetch as $key => $value) {

					$item_stat = ($value['item_status'] == 1 ) ? 'New' : 'Old';
					$photo = ($value['i_photo'] == "") ? "../assets/noimagefound.jpg" : "../uploads/" . $value['i_photo'];
					
					$data[] = array('e_photo'=>$photo,
									'e_deviceid'=>$value['i_deviceID'],
									'e_category'=>ucwords($value['i_category']),
									'e_brand'=>ucwords($value['i_brand']),
									'e_description'=>$value['i_description'],
									'e_stock'=>$value['item_rawstock'],
									'e_stockleft'=>$value['items_stock'],
									'e_type'=>ucwords($value['i_type']),
									'e_status'=>$item_stat,
									'e_model'=>ucwords($value['i_model']),
									'e_mr'=>ucwords($value['i_mr']),
									'e_price'=>ucwords($value['i_price'])
									);
				}
				echo json_encode($data);
			}
		}

		public function display_equipment_new()
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM item_stock 
									LEFT JOIN item ON item.id = item_stock.item_id
									WHERE item_stock.item_status = ?');

			// $sql = $conn->prepare('SELECT * FROM equipment_inventory
			// 						LEFT JOIN equipment ON equipment.id = equipment_inventory.equipment_id
			// 						LEFT JOIN room ON room.id = equipment.room_id
			// 						WHERE equipment_inventory.status = ?');
			$sql->execute(array(1));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				
				foreach ($fetch as $key => $value) {
					$data['data'][] = array($value['i_model'],$value['i_category'],$value['i_brand'],$value['item_rawstock'],$value['items_stock']);
				}
				echo json_encode($data);

			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}
		

		public function display_equipment_old()
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM item_stock 
									LEFT JOIN item ON item.id = item_stock.item_id
									WHERE item_stock.item_status = ?');

			// $sql = $conn->prepare('SELECT * FROM equipment_inventory
			// 						LEFT JOIN equipment ON equipment.id = equipment_inventory.equipment_id
			// 						LEFT JOIN room ON room.id = equipment.room_id
			// 						WHERE equipment_inventory.status = ?');
			$sql->execute(array(2));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				
				foreach ($fetch as $key => $value) {
					$data['data'][] = array($value['i_model'],$value['i_category'],$value['i_brand'],$value['item_rawstock'],$value['items_stock']);
				}
				echo json_encode($data);

			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}

		public function display_equipment_lost()
		{
			global $conn;

			$sql = $conn->prepare('SELECT *, SUM(item_inventory.inventory_itemstock) as inv_stock FROM item_inventory
									LEFT JOIN item ON item.id = item_inventory.item_id 
									WHERE item_inventory.inventory_status = ? GROUP BY item_inventory.inventory_status');
			$sql->execute(array(3));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				
				foreach ($fetch as $key => $value) {
					$data['data'][] = array($value['i_model'],$value['i_category'],$value['i_brand'],$value['inv_stock'],$value['item_remarks']);
				}
				echo json_encode($data);

			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}
		public function display_equipment_damaged()
		{
			global $conn;

			$sql = $conn->prepare('SELECT *, SUM(item_inventory.inventory_itemstock) as inv_stock FROM item_inventory
									LEFT JOIN item ON item.id = item_inventory.item_id 
									WHERE item_inventory.inventory_status = ? GROUP BY item_inventory.inventory_status');
			$sql->execute(array(4));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				
				foreach ($fetch as $key => $value) {
					$data['data'][] = array($value['i_model'],$value['i_category'],$value['i_brand'],$value['inv_stock'],$value['item_remarks']);
				}
				echo json_encode($data);

			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}


		public function display_equipment_pulled()
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM equipment_inventory
									LEFT JOIN equipment ON equipment.id = equipment_inventory.equipment_id
									LEFT JOIN room ON room.id = equipment.room_id
									WHERE equipment_inventory.status = ?');
			$sql->execute(array('Pulled-Out'));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				
				foreach ($fetch as $key => $value) {
					$data['data'][] = array($value['e_model'],$value['e_category'],$value['e_brand'],$value['qty'],$value['remarks']);
				}
				echo json_encode($data);

			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}

		public function display_equipment_all()
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM item
									LEFT JOIN item_stock ON item_stock.item_id = item.id
									GROUP BY item.i_category');
			$sql->execute(array(4));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();

			if($count > 0){
				foreach ($fetch as $key => $value) {
					$unusable = $value['item_rawstock'] - $value['items_stock'];
					$data['data'][] = array($value['i_category'],$value['items_stock'],$unusable,$value['item_rawstock']);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}

		}

		public function display_item_borrow()
		{
			global $conn;
			$sql = $conn->prepare('SELECT *, item_stock.id as re_id, item.id as itemid FROM item_stock
									LEFT JOIN item ON item.id = item_stock.item_id
									WHERE item_stock.items_stock > ?  ORDER BY item_status ASC');
			$sql->execute(array(0));
			$row = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($row > 0){
				foreach ($fetch as $key => $value) {
					$itemstatus = ($value['item_status'] == 1) ? 'New' : 'Old';
					$data[] = array('id'=> $value['itemid'] . "||" . $value['re_id'],
									'deviceid'=>$value['i_deviceID'],
									'category'=>ucwords($value['i_category']),
									'brand'=>ucwords($value['i_brand']),
									'stockleft'=>$value['items_stock'],
									'status'=>$itemstatus);
				}
				echo json_encode($data);
			}else{
				echo 0;
			}
		}

		public function display_borrow()
		{
			global $conn; 
			$sql = $conn->prepare('	SELECT *, GROUP_CONCAT(item.i_deviceID, " - " ,item.i_category,  "<br/>") item_borrow FROM borrow
								 	LEFT JOIN item_stock ON item_stock.id = borrow.stock_id
								 	LEFT JOIN item ON item.id = item_stock.item_id
								 	LEFT JOIN member ON member.id = borrow.member_id
								 	LEFT JOIN room ON room.id = borrow.room_assigned
								 	WHERE borrow.status = ? GROUP BY borrow.borrowcode');
			$sql->execute(array(1));
			$fetch = $sql->fetchAll();
			$count = $sql->rowCount();
			if($count > 0){
				foreach ($fetch as $key => $value) {
				$button = "<button class='btn btn-primary' data-id='".$value['member_id']."/".$value['borrowcode']."'>
							Return
							<i class='fa fa-chevron-right'></i>
							</button>";
				$date = date('F d,Y H:i:s A', strtotime($value['date_borrow']));
					$data['data'][] = array($value['m_fname'].' '.$value['m_lname'],$date,$value['item_borrow'],$button,ucwords($value['rm_name']));
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}

		public function display_return()
		{
			global $conn; 
			if(isset($_POST['month']) && isset($_POST['year']) && $_POST['month'] != "" && $_POST['year'] != "")
			{
				$sql = $conn->prepare("SELECT *, GROUP_CONCAT(item.i_deviceID, ' - ' ,item.i_category,  '<br/>') item_borrow FROM borrow
								 	LEFT JOIN item_stock ON item_stock.id = borrow.stock_id
								 	LEFT JOIN item ON item.id = item_stock.item_id
								 	LEFT JOIN member ON member.id = borrow.member_id
								 	WHERE MONTH(borrow.date_borrow) = ".$_POST['month']." AND  YEAR(borrow.date_borrow) = ".$_POST['year']."
								 	GROUP BY borrow.borrowcode");
			}
			else if(isset($_POST['month']) && $_POST['month'] != "")
			{
				$sql = $conn->prepare("SELECT *, GROUP_CONCAT(item.i_deviceID, ' - ' ,item.i_category,  '<br/>') item_borrow FROM borrow
								 	LEFT JOIN item_stock ON item_stock.id = borrow.stock_id
								 	LEFT JOIN item ON item.id = item_stock.item_id
								 	LEFT JOIN member ON member.id = borrow.member_id
								 	WHERE MONTH(borrow.date_borrow) = ".$_POST['month']." GROUP BY borrow.borrowcode");
			}
			else if(isset($_POST['year']) && $_POST['year'] != "")
			{
				$sql = $conn->prepare("SELECT *, GROUP_CONCAT(item.i_deviceID, ' - ' ,item.i_category,  '<br/>') item_borrow FROM borrow
								 	LEFT JOIN item_stock ON item_stock.id = borrow.stock_id
								 	LEFT JOIN item ON item.id = item_stock.item_id
								 	LEFT JOIN member ON member.id = borrow.member_id
								 	WHERE YEAR(borrow.date_borrow) = ".$_POST['year']."
								 	GROUP BY borrow.borrowcode");
			}
			else
			{
				$sql = $conn->prepare('	SELECT *, GROUP_CONCAT(item.i_deviceID, " - " ,item.i_category,  "<br/>") item_borrow FROM borrow
								 	LEFT JOIN item_stock ON item_stock.id = borrow.stock_id
								 	LEFT JOIN item ON item.id = item_stock.item_id
								 	LEFT JOIN member ON member.id = borrow.member_id
								 	GROUP BY borrow.borrowcode');
			}

			$sql->execute();
			$fetch = $sql->fetchAll();
			$count = $sql->rowCount();
			if($count > 0){
				foreach ($fetch as $key => $value) {
				// $button = "<button class='btn btn-primary' data-id='".$value['member_id']."'>
				// 			Return
				// 			<i class='fa fa-chevron-right'></i>
				// 			</button>";
				$date = ($value['date_return'] == 'NULL' || $value['date_return'] == NULL) ? " --- " : date('F d,Y H:i:s A', strtotime($value['date_return']));
				$date2 = date('F d,Y H:i:s A', strtotime($value['date_borrow']));
					$data['data'][] = array($value['m_fname'].' '.$value['m_lname'],$value['item_borrow'],$date,$date2);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}
		
		public function pending_reservation()
		{
			global $conn; 
			$sql = $conn->prepare('	SELECT *, GROUP_CONCAT(item.i_deviceID, " - " ,item.i_category,  "<br/>") item_borrow FROM reservation
								 	LEFT JOIN item_stock ON item_stock.id = reservation.stock_id
								 	LEFT JOIN item ON item.id = item_stock.item_id
								 	LEFT JOIN member ON member.id = reservation.member_id
								 	LEFT JOIN room ON room.id = reservation.assign_room
								 	WHERE reservation.status = ? GROUP BY reservation.reservation_code ORDER BY reservation.reserve_date ASC');
			$sql->execute(array(0));
			$fetch = $sql->fetchAll();
			$count = $sql->rowCount();
			if($count > 0){
				foreach ($fetch as $key => $value) {
				$button = "<button class='btn btn-primary btn-accept' data-id='".$value['reservation_code']."'>
							Accept
							<i class='fa fa-chevron-right'></i>
							</button>
							<button class='btn btn-danger btn-cancel' data-id='".$value['reservation_code']."'>
							Cancel
							<i class='fa fa-remove'></i>
							</button>";
				$date = date('F d,Y H:i:s A', strtotime($value['reserve_date'].' '.$value['reservation_time']));
					$data['data'][] = array($value['m_fname'].' '.$value['m_lname'],$value['item_borrow'],$date,ucwords($value['rm_name']),$button);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}

		public function accept_reservation()
		{
			global $conn; 
			$sql = $conn->prepare('		SELECT *, GROUP_CONCAT(item.i_deviceID, " - " ,item.i_category,  "<br/>") item_borrow FROM reservation
								 	LEFT JOIN item_stock ON item_stock.id = reservation.stock_id
								 	LEFT JOIN item ON item.id = item_stock.item_id
								 	LEFT JOIN member ON member.id = reservation.member_id
								 	LEFT JOIN room ON room.id = reservation.assign_room
								 	WHERE reservation.status = ? GROUP BY reservation.reservation_code');
			$sql->execute(array(1));
			$fetch = $sql->fetchAll();
			$count = $sql->rowCount();
			if($count > 0){
				foreach ($fetch as $key => $value) {
				$button = "<button class='btn btn-primary borrowreserve' data-id='".$value['reservation_code']."'>
							Borrow
							<i class='fa fa-chevron-right'></i>
							</button>";
				$date = date('F d,Y H:i:s A', strtotime($value['reserve_date'].' '.$value['reservation_time']));
					$data['data'][] = array($value['m_fname'].' '.$value['m_lname'],$value['item_borrow'],$date,ucwords($value['rm_name']),$button);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}

		public function tbluser_reservation()
		{
			global $conn;
			session_start();
			$session = $_SESSION['member_id'];
			$sql = $conn->prepare('	SELECT *,reservation.status as stat, GROUP_CONCAT(item.i_deviceID, " - " ,item.i_category,  "<br/>") item_borrow FROM reservation
								 	LEFT JOIN item_stock ON item_stock.id = reservation.stock_id
								 	LEFT JOIN item ON item.id = item_stock.item_id
								 	LEFT JOIN member ON member.id = reservation.member_id
								 	LEFT JOIN room ON room.id = reservation.assign_room
								 	LEFT JOIN reservation_status ON reservation_status.reservation_code = reservation.reservation_code
								 	WHERE reservation.member_id = ? GROUP BY reservation.reservation_code');
			$sql->execute(array($session));
			$fetch = $sql->fetchAll();
			$count = $sql->rowCount();
			if($count > 0){
				foreach ($fetch as $key => $value) {

					if($value['stat'] == 0){
						$status = 'Pending';
					}else if($value['stat'] == 1){
						$status = 'Accepted';
					}else if($value['stat'] == 2){
						$status = 'Cancelled';
					}else {
						$status = 'Borrowed';
					}
				
				$date = date('F d,Y H:i:s A', strtotime($value['reserve_date'].' '.$value['reservation_time']));
					$data['data'][] = array($date,$value['item_borrow'],ucwords($value['rm_name']),$value['remark'],$status);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}

		public function chart_borrow()
		{
			global $conn;

			$sql = $conn->prepare('SELECT *, COUNT(date_borrow) as numberborrow FROM borrow  GROUP BY CAST(date_borrow AS DATE) ');
			$sql->execute();
			$get = $sql->fetchAll();
			$count = $sql->rowCount();


			if($count > 0){

				foreach ($get as $key => $value) {
					$date = date('Y-m-d', strtotime($value['date_borrow']));
					$val[] = array('date'=>$date,'value'=>$value['numberborrow']);
				}
				echo json_encode($val);

			}else{
				$val[] = array();
				echo json_encode($val);
			}

		}

		public function chart_return()
		{
			global $conn;

			$sql = $conn->prepare('SELECT *, COUNT(date_borrow) as numberborrow FROM borrow WHERE status = ? GROUP BY CAST(date_borrow AS DATE) ');
			$sql->execute(array(2));
			$get = $sql->fetchAll();
			$count = $sql->rowCount();

			if($count > 0){

				foreach ($get as $key => $value) {
					$date = date('Y-m-d', strtotime($value['date_borrow']));
					$val[] = array('date'=>$date,'value'=>$value['numberborrow']);
				}
				echo json_encode($val);

			}else{
				$val[] = array();
				echo json_encode($val);
			}

		}


		public function chart_inventory()
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM item GROUP BY i_category');
			$sql->execute();
			$get = $sql->fetchAll();
			$count = $sql->rowCount();

			if($count > 0){

				foreach ($get as $key => $value) {
					$val[] = array('country'=>$value['i_category'],'litres'=>$value['item_rawstock']);
				}
				echo json_encode($val);

			}else{
				$val[] = array();
				echo json_encode($val);
			}
		}

		public function count_pendingres()
		{
			global $conn;
			$sql = $conn->prepare('SELECT *, COUNT(id) as total FROM reservation WHERE status = ?');
			$sql->execute(array(0));
			$count = $sql->rowCount();
			$fetch = $sql->fetch();

			if($count > 0){
				echo $fetch['total'];
			}else{
				echo "0";
			}
		}
		public function count_acceptres()
		{
			global $conn;
			$sql = $conn->prepare('SELECT *, COUNT(id) as total FROM reservation WHERE status = ?');
			$sql->execute(array(1));
			$count = $sql->rowCount();
			$fetch = $sql->fetch();

			if($count > 0){
				echo $fetch['total'];
			}else{
				echo "0";
			}
		}
		public function count_cancelres()
		{
			global $conn;
			$sql = $conn->prepare('SELECT *, COUNT(id) as total FROM reservation WHERE status = ?');
			$sql->execute(array(2));
			$count = $sql->rowCount();
			$fetch = $sql->fetch();

			if($count > 0){
				echo $fetch['total'];
			}else{
				echo "0";
			}
		}
		public function count_client()
		{
			global $conn;
			$sql = $conn->prepare('SELECT *, COUNT(id) as total FROM member WHERE m_status = ?');
			$sql->execute(array(1));
			$count = $sql->rowCount();
			$fetch = $sql->fetch();

			if($count > 0){
				echo $fetch['total'];
			}else{
				echo "0";
			}
		}

		public function table_history()
		{
			global $conn;
			$sql = $conn->prepare("SELECT * FROM history_logs 
									LEFT JOIN user ON user.id = history_logs.user_id
									ORDER BY history_logs.date_created ASC");
			$sql->execute();
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				foreach ($fetch as $key => $value) {
					$date = date('M d,Y H:i:s A', strtotime($value['date_created']));
					$data['data'][] = array($value['name'],$value['description'],$date);	
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}

		public function dashboard_history()
		{
			global $conn;
			$sql = $conn->prepare("SELECT * FROM history_logs 
									LEFT JOIN user ON user.id = history_logs.user_id
									ORDER BY history_logs.date_created ASC LIMIT 0,10");
			$sql->execute();
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($count > 0){
				foreach ($fetch as $key => $value) {
					$date = date('M d,Y H:i:s', strtotime($value['date_created']));
					$data[] = array($date.' - '.$value['name'].' - '.$value['description']);
				}
				echo json_encode($data);
			}else{
				echo "0";
			}
		}

		public function view_equipment($id)
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM room WHERE rm_name = ?');
			$sql->execute(array('room 310'));
			$fetch = $sql->fetchAll();
			foreach ($fetch as $key => $value) {
				$data[] = array(ucwords($value['rm_name']),$value['id']);
			}
			echo json_encode($data);
		}


		public function display_room_reserve()
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM room WHERE rm_name != ? ORDER BY rm_name ASC');
			$sql->execute(array('room 310'));
			$fetch = $sql->fetchAll();

			foreach ($fetch as $key => $value) {
				$data[] = array(ucwords($value['rm_name']),$value['id']);
			}
			echo json_encode($data);

		}

		public function table_dashboard()
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM item_stock
									LEFT JOIN item ON item.id = item_stock.item_id
									WHERE item_stock.items_stock > ? ');
			$sql->execute(array(0));
			$row = $sql->rowCount();
			$fetch = $sql->fetchAll();
			if($row > 0){
				foreach ($fetch as $key => $value) {
					$status = ($value['item_status'] == 1) ? 'New' : 'Old' ;
					$data['data'][] = array($value['i_model'],$value['i_category'],$value['i_brand'],$value['i_description'],$value['items_stock'],$status);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}

		}

		public function tbl_member_profile($id)
		{
			global $conn;

			$sql = $conn->prepare('SELECT *, borrow.status as statusb FROM borrow
								 	LEFT JOIN item_stock ON item_stock.id = borrow.stock_id
								 	LEFT JOIN item ON item.id = item_stock.item_id
								 	LEFT JOIN member ON member.id = borrow.member_id
								 	LEFT JOIN room ON room.id = borrow.room_assigned
								 	WHERE borrow.member_id = ? GROUP BY borrow.borrowcode');
			$sql->execute(array($id));
			$row = $sql->rowCount();
			$fetch = $sql->fetchAll();

			if($row > 0){
				foreach ($fetch as $key => $value) {
					$date = date('F d,Y H:i:s A', strtotime($value['date_borrow']));
					$status = ($value['statusb'] == 1) ? 'Borrow' : 'Returned';
					$data['data'][] = array($date,$value['i_brand'].' - '.$value['i_category'],ucwords($value['rm_name']),$status); 
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}

		}

		public function display_rooms()
		{
			global $conn;

			$sql = $conn->prepare('SELECT * FROM room WHERE rm_name != ? ORDER BY rm_name ASC');
			$sql->execute(array('room 310'));
			$fetch = $sql->fetchAll();
				foreach ($fetch as $key => $value) {
					$data[] = array($value['id'],ucwords($value['rm_name']));
				}
				echo json_encode($data);
		}

		public function tbluser_inbox()
		{
			global $conn;
			session_start();

			$member_id = $_SESSION['member_id'];

			$sql = $conn->prepare('SELECT * FROM reservation_status
									LEFT JOIN reservation ON  reservation.reservation_code = reservation_status.reservation_code
									WHERE reservation.member_id = ?');
			$sql->execute(array($member_id));
			$fetch = $sql->fetchAll();
			$row = $sql->rowCount();

			if($row > 0 ){
				foreach ($fetch as $key => $value) {
					$status = ($value['res_status'] == 2) ? 'Cancelled' : 'Accepted' ;
					$data['data'][] = array($value['reservation_code'],$status, $value['remark']);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
			
		}

		public function count_reservation()
		{
			global $conn;
			$date = date('Y-m-d');

			$sql = $conn->prepare('SELECT COUNT(id) as count FROM reservation WHERE status = ?  AND r_date >= ?');
			$sql->execute(array(0,$date));
			$fetch = $sql->fetch();
			$row = $sql->rowCount();
			if($row > 0){
				echo $fetch['count'];
			}else{
				echo '0';
			}
		}

		public function count_due_borrow()
		{
			global $conn;
			$date = date('Y-m-d h:i:s');

			$sql = $conn->prepare('SELECT COUNT(*) as countDue FROM borrow WHERE time_limit < ? AND status = ?');
			$sql->execute(array($date,1));
			$fetch = $sql->fetch();
			echo $fetch['countDue'];
		}

		public function display_inventory_transfer()
		{
			global $conn;

			if(isset($_POST['month']) && isset($_POST['year']) && $_POST['month'] != "" && $_POST['year'] != "")
			{
				$sql = $conn->prepare('SELECT * FROM item_transfer 
									LEFT JOIN item ON item.id = item_transfer.t_itemID
									LEFT JOIN room ON room.id = item_transfer.t_roomID
									LEFT JOIN user ON user.id = item_transfer.userid
									WHERE MONTH(item_transfer.date_transfer) = '.$_POST['month'].' AND YEAR(item_transfer.date_transfer) = '.$_POST['year'].' AND item_transfer.t_status = ?');
			}
			else if(isset($_POST['month']) && $_POST['month'] != "")
			{
				$sql = $conn->prepare('SELECT * FROM item_transfer 
									LEFT JOIN item ON item.id = item_transfer.t_itemID
									LEFT JOIN room ON room.id = item_transfer.t_roomID
									LEFT JOIN user ON user.id = item_transfer.userid
									WHERE MONTH(item_transfer.date_transfer) = '.$_POST['month'].' AND item_transfer.t_status = ?');
			}
			else if(isset($_POST['year']) && $_POST['year'] != "")
			{
				$sql = $conn->prepare('SELECT * FROM item_transfer 
									LEFT JOIN item ON item.id = item_transfer.t_itemID
									LEFT JOIN room ON room.id = item_transfer.t_roomID
									LEFT JOIN user ON user.id = item_transfer.userid
									WHERE YEAR(item_transfer.date_transfer) = '.$_POST['year'].' AND item_transfer.t_status = ?');
			}
			else
			{
				$sql = $conn->prepare('SELECT * FROM item_transfer 
									LEFT JOIN item ON item.id = item_transfer.t_itemID
									LEFT JOIN room ON room.id = item_transfer.t_roomID
									LEFT JOIN user ON user.id = item_transfer.userid
									WHERE item_transfer.t_status = ?');
			}
			
			$sql->execute(array(1));
			$count = $sql->rowCount();
			$fetch = $sql->fetchAll();

			if($count > 0){
				foreach ($fetch as $key => $value) {
					$data['data'][] = array($value['i_model'],$value['i_category'],$value['i_brand'],$value['t_quantity'],ucwords($value['rm_name']),$value['name'],$value['personincharge'],$value['date_transfer']);
				}
				echo json_encode($data);
			}else{
				$data['data'] = array();
				echo json_encode($data);
			}
		}

		public function loadReservationsJSON()
		{
			global $conn; 
			$sql = $conn->prepare('SELECT *, GROUP_CONCAT(item.i_category,  "") item_borrow FROM reservation
								 	LEFT JOIN item_stock ON item_stock.id = reservation.stock_id
								 	LEFT JOIN item ON item.id = item_stock.item_id
								 	LEFT JOIN member ON member.id = reservation.member_id
								 	LEFT JOIN room ON room.id = reservation.assign_room
								 	WHERE reservation.status = ? GROUP BY reservation.reservation_code');
			$sql->execute(array(1));
			$fetch = $sql->fetchAll();
			$count = $sql->rowCount();
			$reserveArr = array();
			if($count > 0)
			{
				foreach($fetch as $reservation)
				{
					$reserveArr[] = array(
						"title" => $reservation['item_borrow'] . " " . ucwords($reservation['rm_name']),
						"allDay" => false,
						"start" => date("Y-m-d H:i:s",strtotime($reservation['reserve_date'].' '.$reservation['reservation_time'])),
						"backgroundColor" => "#0073b7",
						"borderColor" => "#0073b7",
						"url" => "../views/reservation"
					);
				}

				echo json_encode($reserveArr);

			}
			else
			{
				echo json_encode($reserveArr);
			}
		}

		public function chartFrequency()
		{
			global $conn;

			$sql = $conn->prepare('SELECT *, (SELECT COUNT(*) FROM borrow WHERE borrow.item_id = item.id AND borrow.status = ?) AS borrowCount FROM item GROUP BY i_category');
			$sql->execute(array(1));
			$get = $sql->fetchAll();
			$count = $sql->rowCount();

			if($count > 0){

				foreach ($get as $key => $value) {
					$val[] = array('country'=>$value['i_category'],'litres'=>$value['borrowCount']);
				}
				echo json_encode($val);

			}else{
				$val[] = array();
				echo json_encode($val);
			}
		}
	}

	$display = new display();

	$key = trim($_POST['key']);

	switch ($key) {

		case 'display_room';
		$display->display_room();
		break;

		case 'display_member';
		$display->display_member();
		break;

		case 'display_user';
		$display->display_user();
		break;

		case 'display_roomtype';
		$display->display_roomtype();
		break;

		case 'display_roomtype1';
		$id = $_POST['id'];
		$display->display_roomtype1($id);
		break;

		case 'display_equipment';
		$display->display_equipment();
		break;

		case 'display_roominfo';
		$id = $_POST['id'];
		$name = $_POST['name'];
		$display->display_roominfo($id,$name);
		break;

		case 'display_newtransaction';
		$display->display_newtransaction();
		break;
		
		case 'display_memberselect';
		$display->display_memberselect();
		break;

		case 'display_equipmentinfo';
		$id = trim($_POST['id']);
		$display->display_equipmentinfo($id);
		break;

		case 'display_equipment_new';
		$display->display_equipment_new();
		break;	

		case 'display_equipment_old';
		$display->display_equipment_old();
		break;	

		case 'display_equipment_lost';
		$display->display_equipment_lost();
		break;	

		case 'display_equipment_damaged';
		$display->display_equipment_damaged();
		break;	

		case 'display_equipment_pulled';
		$display->display_equipment_pulled();
		break;	

		case 'display_equipment_all';
		$display->display_equipment_all();
		break;

		case 'display_item_borrow';
		$display->display_item_borrow();
		break;

		case 'display_borrow';
		$display->display_borrow();
		break;

		case 'display_return';
		$display->display_return();
		break;

		case 'pending_reservation';
		$display->pending_reservation();
		break;

		case 'accept_reservation';
		$display->accept_reservation();
		break;

		case 'tbluser_reservation';
		$display->tbluser_reservation();
		break;

		case 'chart_borrow';
		$display->chart_borrow();
		break;

		case 'chart_return';
		$display->chart_return();
		break;

		case 'chart_inventory';
		$display->chart_inventory();
		break;

		case 'count_pendingres':
		$display->count_pendingres();
		break;

		case 'count_acceptres':
		$display->count_acceptres();
		break;

		case 'count_cancelres':
		$display->count_cancelres();
		break;

		case 'count_client':
		$display->count_client();
		break;

		case 'table_history';
		$display->table_history();
		break;

		case 'dashboard_history';
		$display->dashboard_history();
		break;

		case 'view_equipment';
		$id = $_POST['id'];
		$display->view_equipment($id);
		break;

		case 'display_room_reserve';
		$display->display_room_reserve();
		break;

		case 'table_dashboard';
		$display->table_dashboard();
		break;

		case 'tbl_member_profile';
		$id = $_POST['id'];
		$display->tbl_member_profile($id);
		break;

		case 'display_rooms';
		$display->display_rooms();
		break;

		case 'tbluser_inbox';
		$display->tbluser_inbox();
		break;

		case 'count_reservation';
		$display->count_reservation();
		break;

		case 'display_inventory_transfer';
		$display->display_inventory_transfer();
		break;

		case 'load_reservations_json';
		$display->loadReservationsJSON();
		break;

		case 'chart_frequency';
		$display->chartFrequency();
		break;

		case 'count_due_borrow';
		$display->count_due_borrow();
		break;
	}




?>