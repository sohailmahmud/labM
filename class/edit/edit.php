<?php
	require_once "../config/config.php";

	// 1 == success
	// 2 == exist
	// 0 == failed
	
	/**
	* 
	*/
	class edit
	{
		
		public function edit_room($edit_rm_name,$edit_rm_id)
		{
			global $conn;

			session_start();
			$h_tbl = 'room';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$ab = $conn->prepare("SELECT * FROM room WHERE id = ? ");
			$ab->execute(array($edit_rm_id));
			$fetchab = $ab->fetch();

			$check = $conn->prepare("SELECT * FROM room WHERE rm_name = ? ");
			$check->execute(array($edit_rm_name));
			$check_fetch = $check->fetch();
			$check_row = $check->rowCount();

			$h_desc = 'edit room '.$fetchab['rm_name'].' to '. $edit_rm_name;

			if($check_row <= 0){

				$sql = $conn->prepare('UPDATE room SET rm_name = ? WHERE id = ?;
										INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?)');
				$sql->execute(array($edit_rm_name,$edit_rm_id,$h_desc,$h_tbl,$sessionid,$sessiontype));
				$count = $sql->rowCount();
				if($count > 0){
					echo "1";
				}else{
					echo "0";
				}

			}else{
				echo '2';
			}

		}

		public function edit_moveroom($assign,$tbl_id,$move_item,$current)
		{	
			global $conn;

			session_start();
			$h_desc = 'move room';
			$h_tbl = 'room';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$check = $conn->prepare('SELECT * FROM room_equipment WHERE equipment_id = ? AND room_id = ?');
			$check->execute(array($tbl_id,$assign));
			$check_row = $check->rowCount();
			$get = $check->fetch();
			$update = $get['re_quantity'];
			$newitem = $update + $move_item;

			if($check_row <= 0){
				$insert = $conn->prepare('INSERT INTO room_equipment(equipment_id,room_id,re_quantity) VALUES(?,?,?);
										  UPDATE room_equipment SET re_quantity = (re_quantity - ?) WHERE equipment_id = ? AND room_id = ?;
										  INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?) ');
				$insert->execute(array($tbl_id,$assign,$move_item,$move_item,$tbl_id,$current,$h_desc,$h_tbl,$sessionid,$sessiontype));
				$row = $insert->rowCount();
				echo $row;
			}else{
				$update = $conn->prepare('UPDATE room_equipment SET re_quantity = ? WHERE equipment_id = ? AND room_id = ?;
										  UPDATE room_equipment SET re_quantity = (re_quantity - ?) WHERE equipment_id = ? AND room_id = ?;
										  INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?) ');
				$update->execute(array($newitem,$tbl_id,$assign,$move_item,$tbl_id,$current,$h_desc,$h_tbl,$sessionid,$sessiontype));
				$update_row = $update->rowCount();
				echo $update_row;

			}

			// $sql = $conn->prepare('UPDATE equipment SET room_id = ? WHERE id = ?');
			// $sql->execute(array($assign,$tbl_id));
			// $count = $sql->rowCount();
			// echo ($count > 0) ? 1 : 0 ;
		}

		public function edit_equipmentstatus($change_status,$change_room,$id,$item_emove,$e_remarks)
		{
			global $conn;

			session_start();
			$h_desc = 'update equipment status';
			$h_tbl = 'items';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$sql = $conn->prepare('SELECT * FROM item_stock WHERE id = ?');
			$sql->execute(array($id));
			$fetch = $sql->fetch();
			$row = $sql->rowCount();
			$itemID = $fetch['item_id'];
			$itemStatus = $fetch['item_status'];
			$roomId = $fetch['room_id'];
				if($row > 0){

					if($change_status == 2){
						$checkitem = $conn->prepare('SELECT * FROM item_stock WHERE item_id = ? AND item_status = ?');
						$checkitem->execute(array($itemID,$change_status));
						$checkitemrow = $checkitem->rowCount();
						if($checkitemrow > 0){
							$checkupdate = $conn->prepare('UPDATE item_stock SET items_stock = (items_stock + ?) WHERE item_id = ? AND item_status = ?');
							$checkupdate->execute(array($item_emove,$itemID,$change_status));
							$count1 = $checkupdate->rowCount();
							if($count1 > 0){
								$inveadd = $conn->prepare('INSERT INTO item_inventory (item_id, inventory_itemstock, inventory_status, item_remarks) VALUES(?,?,?,?)');
								$inveadd->execute(array($itemID,$item_emove,$change_status,$e_remarks));
								$invrow = $inveadd->rowCount();
								if($invrow > 0){
									$updateitem = $conn->prepare('UPDATE item_stock SET items_stock = (items_stock - ?) WHERE item_id = ? AND item_status = ?');
									$updateitem->execute(array($item_emove,$itemID,$itemStatus));
									$updateitemrow = $updateitem->rowCount();
									
									echo ($updateitemrow == 1) ? 'Succesfully changed' : 'Failed to change status' ;
								}
							}

						}else{
							$checkadd = $conn->prepare('INSERT INTO item_stock (item_id,room_id,items_stock,item_status) VALUES(?,?,?,?)');
							$checkadd->execute(array($itemID,$roomId,$item_emove,$change_status));
							$checkaddrow = $checkadd->rowCount();
							if($checkaddrow > 0){
								$inveadd = $conn->prepare('INSERT INTO item_inventory (item_id, inventory_itemstock, inventory_status, item_remarks) VALUES(?,?,?,?)');
								$inveadd->execute(array($itemID,$item_emove,$change_status,$e_remarks));
								$invrow = $inveadd->rowCount();
								if($invrow > 0){
									$updateitem = $conn->prepare('UPDATE item_stock SET items_stock = (items_stock - ?) WHERE item_id = ? AND item_status = ?');
									$updateitem->execute(array($item_emove,$itemID,$itemStatus));
									$updateitemrow = $updateitem->rowCount();
									
									echo ($updateitemrow == 1) ? 'Succesfully changed' : 'Failed to change status' ;
								}
							}
						}
					}else{
						
							$inveadd = $conn->prepare('INSERT INTO item_inventory (item_id, inventory_itemstock, inventory_status, item_remarks) VALUES(?,?,?,?)');
							$inveadd->execute(array($itemID,$item_emove,$change_status,$e_remarks));
							$invrow = $inveadd->rowCount();
							if($invrow > 0){
								$updateitem = $conn->prepare('UPDATE item_stock SET items_stock = (items_stock - ?) WHERE item_id = ? AND item_status = ?');
								$updateitem->execute(array($item_emove,$itemID,$itemStatus));
								$updateitemrow = $updateitem->rowCount();
								
								echo ($updateitemrow == 1) ? 'Succesfully changed' : 'Failed to change status' ;
							}
						
					}

				}
		}


		public function return_item($id)
		{
			global $conn;

			$date = date('Y-m-d H:i:s');
			session_start();
			$session = $_SESSION['admin_id'];

			$h_desc = 'return items';
			$h_tbl = 'borrow';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];


			$ids = explode('/', $id);
			$memid = $ids[0];
			$borrowcode = $ids[1];


			$select = $conn->prepare('SELECT * FROM borrow WHERE borrowcode = ? AND member_id = ?');
			$select->execute(array($borrowcode,$memid));
			$fetch = $select->fetchAll();
			$row = $select->rowCount();
			
			// $insert = $conn->prepare('INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?)');
	  //  	 	$insert->execute(array($h_desc,$h_tbl,$sessionid,$sessiontype));
	  //  	 	$rowcou = $insert->rowCount();

			foreach ($fetch as $key => $value) {
				$equip = $value['stock_id'];
				$sql = $conn->prepare('UPDATE borrow SET status = ?, date_return = ? WHERE borrowcode = ?;
										UPDATE item_stock SET items_stock = (items_stock + ?) WHERE id = ?  ');
				$sql->execute(array(2,$date,$borrowcode,1,$equip));
				$count = $sql->rowCount(); 
				$datakey = $key + 1;

				echo $count;

			}

			
		}

		public function edititem($e_number,$e_id,$e_category,$e_brand,$e_description,$e_type,$e_model,$e_mr,$e_price)
		{
			global $conn;

			session_start();
			$h_desc = 'edit item';
			$h_tbl = 'item';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];


			// $sql = $conn->prepare('UPDATE equipment SET  e_deviceid = ?, e_category = ?, e_brand = ?, e_description = ?, e_type = ?, e_status = ? WHERE id = ?;
			// 					INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?)');
			// $sql->execute(array($e_number,$e_category,$e_brand,$e_description,$e_type,$e_status,$e_id,$h_desc,$h_tbl,$sessionid,$sessiontype));
			// $row = $sql->rowCount();
			// echo $row;

			$sql = $conn->prepare('SELECT * FROM item_stock WHERE id = ?');
			$sql->execute(array($e_id));
			$row = $sql->rowCount();
			$fetch = $sql->fetch();
			$itemID = $fetch['item_id'];

			if($row > 0){
				$updateitem = $conn->prepare('UPDATE item SET i_deviceID = ?, i_model = ?, i_category = ?, i_brand = ?, i_description = ?, i_type = ?, i_mr = ?, i_price = ? WHERE id = ?');
				$updateitem->execute(array($e_number,$e_model,$e_category,$e_brand,$e_description,$e_type,$e_mr,$e_price,$itemID));
				$updateCount = $updateitem->rowCount();
			
			$imageName = $_FILES['e_photo']['name'];
			$extension = pathinfo($imageName, PATHINFO_EXTENSION);
			$tmpData = $_FILES['e_photo']['tmp_name'];
			$fileName = time();
			$fileStatus = move_uploaded_file($tmpData,'../../uploads/'.$fileName.".".$extension);
			
			$file = "";
			
			if($fileStatus):
				$file = $fileName.".".$extension;
				$sql = $conn->prepare('UPDATE item SET i_photo = ? WHERE id = ?');
				$sql->execute(array($file,$itemID));
			endif;
			
				echo $updateCount;
			}



		}

		public function edit_member($sid_number,$fname,$lname,$s_gender,$s_contact,$s_department,$s_type,$yrs,$app_id)
		{

			global $conn;

			session_start();
			$h_desc = 'edit client';
			$h_tbl = 'client';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];


			$sql = $conn->prepare('UPDATE member SET m_school_id = ?, m_fname = ?, m_lname = ?, m_gender = ?, m_contact = ?, m_department = ?, m_year_section = ?, m_type = ? WHERE id = ?;
									INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?)');
			$sql->execute(array($sid_number,$fname,$lname,$s_gender,$s_contact,$s_department,$yrs,$s_type,$app_id,$h_desc,$h_tbl,$sessionid,$sessiontype));
			$row = $sql->rowCount();
			echo $row;
		}

		public function activate_member($id)
		{
			global $conn;

			session_start();
			$h_desc = 'activate client';
			$h_tbl = 'client';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$sql = $conn->prepare('UPDATE member SET m_status = ? WHERE id = ?;
									INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?)');
			$sql->execute(array(1,$id,$h_desc,$h_tbl,$sessionid,$sessiontype));
			$row = $sql->rowCount();
			echo $row;
		}

		public function deactivate_member($id)
		{
			global $conn;

			session_start();
			$h_desc = 'deactivate client';
			$h_tbl = 'client';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$sql = $conn->prepare('UPDATE member SET m_status = ? WHERE id = ?;
									INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?)');
			$sql->execute(array(0,$id,$h_desc,$h_tbl,$sessionid,$sessiontype));
			$row = $sql->rowCount();
			echo $row;
		}


		public function activate_user($id)
		{
			global $conn;

			session_start();
			$h_desc = 'activate user';
			$h_tbl = 'user';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$sql = $conn->prepare('UPDATE user SET status = ? WHERE id = ?;
									INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?)');
			$sql->execute(array(1,$id,$h_desc,$h_tbl,$sessionid,$sessiontype));
			$row = $sql->rowCount();
			echo $row;
		}

		public function deactivate_user($id)
		{
			global $conn;

			session_start();
			$h_desc = 'deactivate user';
			$h_tbl = 'user';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$sql = $conn->prepare('UPDATE user SET status = ? WHERE id = ?;
									INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?)');
			$sql->execute(array(0,$id,$h_desc,$h_tbl,$sessionid,$sessiontype));
			$row = $sql->rowCount();
			echo $row;
		}


		public function edit_user($u_fname,$u_username,$u_type,$u_id)
		{
			global $conn;

			session_start();
			$h_desc = 'edit user';
			$h_tbl = 'user';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$sql = $conn->prepare('UPDATE user SET name = ?, username = ?, type = ? WHERE id = ?;
									INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?)');
			$sql->execute(array($u_fname,$u_username,$u_type,$u_id,$h_desc,$h_tbl,$sessionid,$sessiontype));
			$count = $sql->rowCount();
			echo $count;
		}

		public function changepassword($n_pass,$u_id)
		{
			global $conn;

			session_start();
			$h_desc = 'change user password';
			$h_tbl = 'user';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$sql = $conn->prepare('UPDATE user SET password = ? WHERE id = ?;
									INSERT INTO history_logs(description,table_name,user_id,user_type) VALUES(?,?,?,?)');
			$sql->execute(array($n_pass,$u_id,$h_desc,$h_tbl,$sessionid,$sessiontype));
			$count = $sql->rowCount();
			echo $count;
		}

		public function accept_reservation($code)
		{
			global $conn;

			session_start();
			$h_desc = 'accept client reservation';
			$h_tbl = 'reservation';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$sql = $conn->prepare('UPDATE reservation SET status = ? WHERE reservation_code = ?');
			$sql->execute(array(1,$code));
			$row = $sql->rowCount();
			if($row > 0){
				$add = $conn->prepare('INSERT INTO reservation_status (reservation_code, remark, res_status) VALUES(?,?,?)');
				$add->execute(array($code,'Accepted Reservation',1));
				$addrow = $add->rowCount();

				echo $addrow;
			}
		}

		public function cancel_reservation($remarks_cancel,$codereserve)
		{
			global $conn;

			session_start();
			$h_desc = 'cancel client reservation';
			$h_tbl = 'reservation';
			$sessionid = $_SESSION['admin_id'];
			$sessiontype = $_SESSION['admin_type'];

			$sql = $conn->prepare('UPDATE reservation SET status = ? WHERE reservation_code = ?');
			$sql->execute(array(2,$codereserve));
			$row = $sql->rowCount();
			if($row > 0){
				$add = $conn->prepare('INSERT INTO reservation_status (reservation_code, remark, res_status) VALUES(?,?,?)');
				$add->execute(array($codereserve,$remarks_cancel,2));
				$addrow = $add->rowCount();

				echo $addrow;
			}
		}

		public function transfer_item()
		{
			global $conn;

			session_start();
			$sessionid = $_SESSION['admin_id'];

			$room = $_POST['transfer_room'];
			$id= $_POST['id'];
			$number= $_POST['number_items'];
			$personincharge= $_POST['personincharge'];
			
			$sql = $conn->prepare('SELECT * FROM item_stock WHERE id = ?');
			$sql->execute(array($id));
			$count = $sql->rowCount();
			$fetch = $sql->fetch();
			$itemID = $fetch['item_id'];
			$itemstock = $fetch['items_stock'];
			$stockID = $id;

				if($count > 0){
					if($number > $itemstock){
						echo "Maximum number of stock to be transfer is ".$itemstock;
					}else{
						$insert = $conn->prepare('INSERT INTO item_transfer(t_itemID, t_roomID, t_stockID, t_quantity, personincharge, userid) VALUES(?,?,?,?,?,?)');
						$insert->execute(array($itemID,$room,$stockID,$number,$personincharge,$sessionid));
						$row = $insert->rowCount();
						if($row > 0){
							$update = $conn->prepare('UPDATE item_stock SET items_stock = (items_stock - ?) WHERE id = ?');
							$update->execute(array($number,$id));
							$updateCount = $update->rowCount();
							if($updateCount > 0){
								echo "Succesfully transferred";
							}
						}
					}
				}

			
		}

		public function borrowreserve($code)
		{
			global $conn;

			session_start();
			$sessionid = $_SESSION['admin_id'];

			$up = $conn->prepare('UPDATE reservation SET status = ? WHERE reservation_code = ? ');
			$up->execute(array(3,$code));
			$num = $up->rowCount();
			if($num > 0){
				$sql = $conn->prepare('SELECT * FROM reservation WHERE reservation_code = ?');
				$sql->execute(array($code));
				$fetch = $sql->fetchAll();
					$borrowIds = array();
					foreach ($fetch as $key => $value) {
						$itemID = $value['item_id'];
							$insert = $conn->prepare('INSERT INTO borrow (borrowcode,member_id,item_id,stock_id,user_id,room_assigned,time_limit) VALUES(?,?,?,?,?,?,?)');
							$insert->execute(array($code,$value['member_id'],$value['item_id'],$value['stock_id'],$sessionid,$value['assign_room'],$value['time_limit']));
							$count = $insert->rowCount();
							$borrowIds[] = $conn->lastInsertId();
							if($count > 0){
								$update = $conn->prepare('UPDATE item_stock SET items_stock = (items_stock - ?) WHERE id = ?');
								$update->execute(array(1,$value['stock_id']));
								$row = $update->rowCount();
							}

					}

					echo json_encode(array("response" => 1, "message" => "Successfully Borrowed", "borrowIds" => implode("|",$borrowIds)));
			}
			else{
				echo json_encode(array("response" => 0));
			}

		}

		public function return_transfer($id,$qty_transfer)
		{
			global $conn;
			$sql = $conn->prepare('SELECT * FROM item_transfer WHERE id = ?');
			$sql->execute(array($id));
			$count = $sql->rowCount();
			$fetch = $sql->fetch();
			$qty = $fetch['t_quantity'];
			$stockID = $fetch['t_stockID'];

			if($qty_transfer > $qty ){
				echo "Error number of items to transfer";
			}else{
				if($count > 0){
					$update = $conn->prepare('UPDATE item_stock SET items_stock = (items_stock + ?) WHERE id = ?');
					$update->execute(array($qty_transfer,$stockID));
					$row = $update->rowCount();
						if($row > 0){
							if($qty_transfer == $qty){
								$cont = $conn->prepare('UPDATE item_transfer SET t_status = ? WHERE id = ?');
								$cont->execute(array(2,$id));
								$controw = $cont->rowCount();
								if($controw > 0){
									echo "Succesfully return to Room 310 a";
								}
							}else if($qty_transfer < $qty){
								$cont = $conn->prepare('UPDATE item_transfer SET t_quantity = (t_quantity - ?) WHERE id = ?');
								$cont->execute(array($qty_transfer,$id));
								$controw = $cont->rowCount();
								if($controw > 0){
									echo "Succesfully return to Room 310 b";
								}
							}
						}
				}else{

				}
			}


				
		}

	}

	$edit = new edit();


	$key = trim($_POST['key']);

	switch ($key) {

		case 'edit_room';
		$edit_rm_name = strtolower($_POST['edit_rm_name']);
		$edit_rm_id = $_POST['edit_rm_id'];
		$edit->edit_room($edit_rm_name,$edit_rm_id);
		break;

		case 'edit_moveroom';
		$assign = $_POST['e_assigned1'];
		$tbl_id = $_POST['tbl_id'];
		$move_item = $_POST['move_item'];
		$current = $_POST['current'];
		$edit->edit_moveroom($assign,$tbl_id,$move_item,$current);
		break;

		case 'edit_equipmentstatus';
		$change_status = trim($_POST['change_status']);
		$change_room = $_POST['change_room'];
		$id = trim($_POST['id']);
		$item_emove = trim($_POST['item_emove']);
		$e_remarks = trim($_POST['e_remarks']);
		$edit->edit_equipmentstatus($change_status,$change_room,$id,$item_emove,$e_remarks);
		break;

		case 'return_item';
		$id = $_POST['id'];
		$edit->return_item($id);
		break;

		case 'edititem';
		$e_number = $_POST['e_number'];
		$e_id = $_POST['e_id'];
		$e_category = $_POST['e_category'];
		$e_brand = $_POST['e_brand'];
		$e_description = $_POST['e_description'];
		$e_type = $_POST['e_type'];
		$e_model = $_POST['e_model'];
		$e_mr = $_POST['e_mr'];
		$e_price = $_POST['e_price'];
		
		$edit->edititem($e_number,$e_id,$e_category,$e_brand,$e_description,$e_type,$e_model,$e_mr,$e_price);
		break;

		case 'edit_member';
		$sid_number = $_POST['sid_number'];
		$fname = $_POST['s_fname'];
		$lname = $_POST['s_lname'];
		$s_gender = $_POST['s_gender'];
		$s_contact = $_POST['s_contact'];
		$s_department = $_POST['s_department'];
		$yrs = $_POST['s_year'].'-'.$_POST['s_section'];
		$app_id = $_POST['app_id'];
		$s_type = $_POST['s_type'];
		$edit->edit_member($sid_number,$fname,$lname,$s_gender,$s_contact,$s_department,$s_type,$yrs,$app_id);
		break;

		case 'activate_member';
		$id = $_POST['id'];
		$edit->activate_member($id);
		break;

		case 'deactivate_member';
		$id = $_POST['id'];
		$edit->deactivate_member($id);
		break;

		case 'activate_user';
		$id = $_POST['id'];
		$edit->activate_user($id);
		break;

		case 'deactivate_user';
		$id = $_POST['id'];
		$edit->deactivate_user($id);
		break;

		case 'edit_user';
		$u_fname = $_POST['u_fname'];
		$u_username = $_POST['u_username'];
		$u_type = $_POST['u_type'];
		$u_id = $_POST['u_id'];
		$edit->edit_user($u_fname,$u_username,$u_type,$u_id);
		break;

		case 'changepassword';
		$n_pass = trim(md5($_POST['n_pass']));
		$u_id = $_POST['u_id'];
		$edit->changepassword($n_pass,$u_id);
		break;

		case 'accept_reservation';
		$code = $_POST['code'];
		$edit->accept_reservation($code);
		break;

		case 'cancel_reservation';
		$remarks_cancel = $_POST['remarks_cancel'];
		$codereserve = $_POST['codereserve'];
		$edit->cancel_reservation($remarks_cancel,$codereserve);
		break;

		case 'transfer_item';
		$edit->transfer_item();
		break;

		case 'borrowreserve';
		$code = $_POST['code'];
		$edit->borrowreserve($code);
		break;

		case 'return_transfer';
		$id = $_POST['id'];
		$qty_transfer = $_POST['qty_transfer'];
		$edit->return_transfer($id,$qty_transfer);
		break;
	}