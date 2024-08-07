<?php

	/*
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	mb_internal_encoding('UTF-8');
	*/
	
	
	//echo 'Hello, Vlad!'.'</br>';
	
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	
	
	class CreateEvent
	{
		private $id;
		private $url;
		private $post;
		private $return;
		private $conn;
		private $description;
		
		
		public function __construct($id){
			
				$this->id=$id;
				$this->conn=new mysqli("localhost","username","password","dbname");
				$this->conn->set_charset("utf8mb4");
				$this->url='url';
				$this->post=array(
					'api_id' => 'api_id',
					'api_key' => 'api_key',
					'call' => 'getTicketDetails',
					'id' => $this->id
				);
			
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $this->url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				$data = curl_exec($ch);
				curl_close($ch);
  
				$this->return = json_decode($data, true);
		
		}
		
		public function getDescription(){
			
			$num=$this->id;
			$sql="SELECT master_id FROM table WHERE rel_id=$num";
			$result=$this->conn->query($sql);
			$master_id=$result->fetch_all(MYSQLI_ASSOC)[0]["master_id"];
			
			$sql="SELECT description FROM table WHERE id=$master_id";
			$result=$this->conn->query($sql);
			$this->description=$result->fetch_all(MYSQLI_ASSOC)[0]["description"];
			
			return $this;
			
		}
		
		public function checkEvent(){
			
			$sample=explode('_',$this->return['ticket']['subject']);
			
			if(count($sample)>3){										//если тема длинная,то
			
			
				$data=$sample[count($sample)-1].' 10:00:00';    //возьмем последний элемент со старой датой заезда
				
				$sql="SELECT id,description FROM table WHERE start=\"$data\"";
				$result=$this->conn->query($sql);
				$arr=$result->fetch_all(MYSQLI_ASSOC);   //получаем в массиве событие или несколько событий за эту дату
				$str=$this->return['ticket']['body'];    //body тикета
				
				foreach($arr as $elem){
					
					if(str_replace(["\n",'"'," "],['',''.''],$elem['description']) == str_replace(["\n",'"'," "],['',''.''],$str)){     //если описание совпадает, удаляем событие из календаря
						
						$this->deleteEvent($elem['id']);
						break;
						
					}
					
				}
			
			}
			
			echo 'Скрипт выполнен. Success.';
			
		}
		
		
		public function createEvent(){
			
			$title=$this->return['ticket']['subject'];     //тема тикета
			$description=$this->return['ticket']['body'];  //body тикета
			$sample=explode('_',$this->return['ticket']['subject'])[1];   //промежуточная переменная
			$start=$sample.' 10:00:00';   //начало заезда
			$end=$sample.' 18:00:00';     //конец заезда
			$rel_id=$this->return['ticket']['ticket_number'];  //номер тикета  
			$data='#'.$rel_id.' '.$title.' '.$this->return['ticket']['name'].' '.$this->return['ticket']['email'];  //значение записи - ссылки в календаре на тикет
			
			//$date = new DateTimeImmutable('now', new DateTimeZone('Europe/Riga')); //устанавливаем текущее время
			//$date_created=$date->format('Y-m-d H:i:s');
			
			$date=new DateTime('Europe/Kiev');
	        $date_created=$date->format('Y-m-d H:i:s');
			
			$sql="SELECT MAX(id) as id FROM table"; //находим номер последней записи в таблице
			$result=$this->conn->query($sql);
			$master_id=$result->fetch_all(MYSQLI_ASSOC)[0]['id']+1; //увеличиваем этот номер для вставки новой записи в таблицу событий, т.к. в table id создастся автоматически, а в table надо вставить самим значение master_id
			
			$sql1="INSERT INTO table (group_id,owner_id,title,description,start,end,flag,created) VALUE (1,76,\"$title\",\"$description\",
			\"$start\",\"$end\",1,\"$date_created\")";
			$result1=$this->conn->query($sql1);

			$sql2="INSERT INTO table (master_id,type,rel_id,data) VALUE (\"$master_id\",'Tickets',\"$rel_id\" ,'{\"name\":\"$data\"}')";
			$result2=$this->conn->query($sql2);
			
			return $this;
		
		}
		
		private function deleteEvent($id){
		
			$sql1="DELETE FROM table WHERE id=$id";
			$this->conn->query($sql1);
	
			$sql2="DELETE FROM table WHERE master_id=$id";
			$this->conn->query($sql2);
			
		}
		
			
	}
	
	if(isset($_GET['ticket_id'])&&!empty($_GET['ticket_id'])){
		
		(new CreateEvent($_GET['ticket_id']))->createEvent()->checkEvent();
		
	}else{
		
		echo 'Нет номера тикета!';
		
	}