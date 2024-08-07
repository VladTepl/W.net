<?php
	/*
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	mb_internal_encoding('UTF-8');
	*/
	
	//echo 'Hello, Vlad!'.'<br>';
	
	//echo date('Y-m-d').'<br>';
	
	$date = date_create( date('Y-m-d'));
	date_modify($date, '-1 day');
	
	$date1= date_format($date, 'Y-m-d').' '.'00:00:00';
	$date2= date_format($date, 'Y-m-d').' '.'23:59:59';
	
	
	$servername = "localhost";
	$username = "username";
	$password = "password";
	$dbname = "dbname";
	

	// Создаем подключение к БД
	
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$conn = new mysqli($servername, $username, $password, $dbname);
	

	// Устанавливаем кодировку

	$conn->set_charset("utf8mb4");

	// Проверяем подключение к БД
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	/*
	//считаем колличество тикетов за сутки
	
	$resultAmount=$conn->query("SELECT COUNT(*) as count FROM table WHERE lastupdate > '$date1' AND lastupdate < '$date2' AND dept_id=1 AND status='Closed'");
	$amount=$resultAmount->fetch_array(MYSQLI_ASSOC)['count'];
	
	if(isset($_GET['page'])){
				
				$page=$_GET['page'];
				
			}else{
				
				$page=1;
				
			}
			
			$notesOnPage=20;         //количество тикетов,выводимых в таблицу на одной странице
			
			$from=($page-1)*$notesOnPage=20;
	
			$pageCount=ceil($amount/$notesOnPage);
	*/
	
	
	//получаем все закрытые тикеты  за сутки
			
	$result = $conn->query("SELECT*FROM table WHERE lastupdate  > '$date1' AND lastupdate  < '$date2' AND dept_id=1 AND status='Closed' ORDER BY lastupdate", MYSQLI_USE_RESULT); //все закрытые тикеты за сутки
	$arr=$result->fetch_all(MYSQLI_ASSOC);
	
	$result4='';
	
	foreach($arr as $elem){
		
		$ticket_number=$elem['ticket_number'];
		$arrRelated=getCategoryAndName($ticket_number);
		$total1=staff_depart($ticket_number);
		$test=ticket($ticket_number);
		$tag=getTag($ticket_number);
		$arrCompanyName=getCompanyName($elem['client_id']);
		
		//считаем количество
		$countRelated=count($arrRelated);
		
		if($countRelated > 2){ 
		
			for($i=1;$i< $countRelated;$i++){
		
				$result4 .= '(';
	
					$result4 .= $ticket_number.',';
					//$result4 .= "'".staff_open($ticket_number)."'".',';
					$result4 .= "'".$total1[0]."'".',';
					$result4 .= "'".staff_closed($ticket_number)."'".',';
					$result4 .= "'".$total1[1]."'".',';
					$result4 .= "'".$total1[3]."'".',';
					$result4 .= "'".$total1[2]."'".',';
					$result4 .= "'".$total1[4]."'".',';
					$result4 .= "'".$arrRelated[$i]['rel_id']."'".','."'".$arrRelated[$i]['cat_name']."'".','."'".$arrRelated[$i]['name']."'".',';
					$result4 .= "'".$elem['date']."'".',';
					$result4 .= "'".$elem['lastupdate']."'".',';
					$result4 .= "'".$tag[1]."'".',';
					$result4 .= "'".$tag[2]."'".',';
					$result4 .= "'".$tag[0]."'".',';
					//$result4 .= '<td>' .getRelatedServices($elem['ticket_number']).'</td>';
					//$result4 .= '<td>' .getService_id($elem['id']).'</td>';
					$result4 .= "'".$test[0]."'".',';
					$result4 .= "'".$test[1]."'".',';
					$result4 .= "'".$test[2]."'".',';
					$result4 .= $test[3].',';
					//$result4 .= '<td>' .ticket($ticket_number)[0].'</td>';
					//$result4 .= '<td>' .ticket($ticket_number)[1].'</td>';
					//$result4 .= '<td>' .ticket($ticket_number)[2].'</td>';
					//$result4 .= '<td>' .ticket($ticket_number)[3].'</td>';
					$result4 .= "'".$arrCompanyName[2]."'".',';
					$result4 .= "'".$arrCompanyName[1]."'".',';
					$result4 .= "'".str_replace("'",'',$arrCompanyName[0])."'".',';
					//$result4 .= $elem['client_id'].',';
					//$result4 .= "'".str_replace("'",'',getCompanyName($elem['client_id']))."'".',';
					//$result4 .= '<td>' .getCompanyName(getClientId($ticket_number)).'</td>';
					$result4 .= "'".str_replace("'",'',$elem['subject'])."'";
			
				$result4 .= ')!'; 
			}
			
		}else{
			
				$result4 .= '(';
	
					$result4 .= $ticket_number.',';
					//$result4 .= "'".staff_open($ticket_number)."'".',';
					$result4 .= "'".$total1[0]."'".',';
					$result4 .= "'".staff_closed($ticket_number)."'".',';
					$result4 .= "'".$total1[1]."'".',';
					$result4 .= "'".$total1[3]."'".',';
					$result4 .= "'".$total1[2]."'".',';
					$result4 .= "'".$total1[4]."'".',';
					$result4 .= "'".$arrRelated[$countRelated-1]['rel_id']."'".','."'".$arrRelated[$countRelated-1]['cat_name']."'".','."'".$arrRelated[$countRelated-1]['name']."'".',';
					$result4 .= "'".$elem['date']."'".',';
					$result4 .= "'".$elem['lastupdate']."'".',';
					$result4 .= "'".$tag[1]."'".',';
					$result4 .= "'".$tag[2]."'".',';
					$result4 .= "'".$tag[0]."'".',';
					//$result4 .= '<td>' .getRelatedServices($elem['ticket_number']).'</td>';
					//$result4 .= '<td>' .getService_id($elem['id']).'</td>';
					$result4 .= "'".$test[0]."'".',';
					$result4 .= "'".$test[1]."'".',';
					$result4 .= "'".$test[2]."'".',';
					$result4 .= $test[3].',';
					//$result4 .= '<td>' .ticket($ticket_number)[0].'</td>';
					//$result4 .= '<td>' .ticket($ticket_number)[1].'</td>';
					//$result4 .= '<td>' .ticket($ticket_number)[2].'</td>';
					//$result4 .= '<td>' .ticket($ticket_number)[3].'</td>';
					//$result4 .=$elem['client_id'].',';
					$result4 .= "'".$arrCompanyName[2]."'".',';
					$result4 .= "'".$arrCompanyName[1]."'".',';
					$result4 .= "'".str_replace("'",'',$arrCompanyName[0])."'".',';
					//$result4 .= "'".str_replace("'",'',getCompanyName($elem['client_id']))."'".',';
					//$result4 .= '<td>' .getCompanyName(getClientId($ticket_number)).'</td>';
					$result4 .= "'".str_replace("'",'',$elem['subject'])."'";
			
				$result4 .= ')!';
				}
				
	}
	
	//echo $result4;
	
	//формируем VALUES для SQL запроса
	
	$arr_closed=explode('!',$result4);
	array_pop($arr_closed);
	$str=implode(',',$arr_closed);
	//echo $str.'</br>';
	
	$request=$conn->query("INSERT INTO table (ticket_number,staff_open,staff_closed,staff_noc,time_noc,staff_expl,time_expl,id_product,
	type_product,name_product,date_open,date_closed,tag_zone,tag_client,ticket_tag,support_time,client_time,total_time,sla,id_contact,client_id,company_name,
	subject) VALUES $str");
	
	
	function getTag($ticket_number){
			
			global $conn;
			
			$result3 = $conn->query("SELECT action FROM table WHERE ticket_number='$ticket_number' AND action LIKE '%tag%'"); //выбираем теги
			$arr3=$result3->fetch_all(MYSQLI_ASSOC);
			//var_dump($arr3[0]);
		
			$tag=[];  //сюда запишуться теги
			
			foreach($arr3 as $elem){
			
			$str=explode('"',$elem['action'])[1];
			
				switch($str){
				
					case 'ЗВ Клієнта':
					$tag[0][].=$str;
					$tag[1][].=$str;
					break;
				
					case 'ЗВ Партнера':
					$tag[0][].=$str;
					$tag[1][].=$str;
					break;
					
					case 'ЗВ Гігатранса':
					$tag[0][].=$str;
					$tag[1][].=$str;
					break;
				
					case 'Звернення':
					$tag[0][].=$str;
					$tag[2][].=$str;
					break;
				
					case 'Телефон':
					$tag[0][].=$str;
					$tag[2][].=$str;
					break;
				
					default:
					$tag[0][].=$str;
					break;
			
				}
			
			}
			
			if(!empty($tag[0])){
				
				$tag[0]=implode(',',array_unique($tag[0]));
				
			}
			
			if(!empty($tag[1])){
				
				$tag[1]=implode(',',array_unique($tag[1]));
				
			}
			
			if(!empty($tag[2])){
				
				$tag[2]=implode(',',array_unique($tag[2]));
				
			}
			
			return $tag;
			
	}
	
	function ticket($ticket_number){
			
			global $conn;
			
		$result1 = $conn->query("SELECT*FROM table WHERE ticket_number='$ticket_number'");
		$result2 = $conn->query("SELECT*FROM table WHERE ticket_number='$ticket_number'");
		
		/*
		$result3 = $conn->query("SELECT action FROM table WHERE ticket_number='$ticket_number' AND action LIKE '%tag%'"); //выбираем теги
		$arr3=$result3->fetch_all(MYSQLI_ASSOC);
		//var_dump($arr3[0]);
		
		$tag='';                         //сюда запишуться теги
		
		foreach($arr3 as $elem){
			
			$tag.=explode('"',$elem['action'])[1].', ';
			
		}
		*/
		
		$arr1= $result1->fetch_array(MYSQLI_ASSOC);     //данные по номеру тикета из БД
	
		//echo $arr1['date'].' Идет учет</br>';           //время получения тикета. Не путать со временем первой реакции сотрудника на тикет
	
		$arr2= $result2->fetch_all(MYSQLI_ASSOC);       //логи тикета
			
	
		$arr_time=[];   //массив времени, где элементами являются время остановки и время запуска для рабочего времени тикета
		
		
	
		$arr_time[]=$arr1['date']; //запись времени прихода тикета
		//var_dump($arr_time);
		//echo $arr_time[0].'</br>';
		/*
		function lastKey($arr_time){			//функция нахождения значения последнего ключа в массиве;
			
			$result=array_keys($arr_time);
			
			return $result[count($result)-1];
			
		}
		*/
		foreach($arr2 as $elem){
		
			if(preg_match('#\\sfrom\\s(("Open")|("Client-Reply")|("Answered")|("In-Progress"))\\sto\\s(("In-Progress")|("Hold")|("Done")|("Answered")|("Closed"))\\s#',$elem['action'])){
				
			//echo lastKey($arr_time).'</br>';
				if(lastKey($arr_time)===0){
					
					$arr_time[]=$elem['date'];
					
				}
				if(lastKey($arr_time)!==0 && lastKey($arr_time)%2 === 0){
					
					$arr_time[]=$elem['date'];
			//echo $elem['date'].' Время остановлено</br>';
				} 
				
				
				if(lastKey($arr_time)!==0 && lastKey($arr_time)%2 !==0){
					
					$arr_time[lastKey($arr_time)]=$elem['date'];
					
				}
				
				
			} 
		
			if(preg_match('#\\sfrom\\s(("Hold")|("Done")|("In-Progress")|("Answered")|("Closed"))\\sto\\s(("Client-Reply")|("Open"))\\s#',$elem['action'])){
			
			//echo lastKey($arr_time).'</br>';
			
				if(lastKey($arr_time)!==0 && lastKey($arr_time)%2 !==0){
					
					$arr_time[]=$elem['date'];
					
				}
				if(lastKey($arr_time)!==0 && lastKey($arr_time)%2 === 0){
					
					$arr_time[lastKey($arr_time)]=$elem['date'];
			//echo $elem['date'].' Время остановлено</br>';
				} 
			
			//$arr_time[]=$elem['date'];
			//echo $elem['date'].' Идет учет</br>';
			
			}
			
			
		
		}
		
		//var_dump($arr_time);
	
		
		//echo '</br>';
		$count=count($arr_time);
	
		//echo $count.'</br>';
		
		//var_dump($arr_time);
	
		//var_dump(lastKey($arr_time));
	
		//находим рабочее время тикета
	
		$time_work=strtotime($arr_time[1])-strtotime($arr_time[0]);
		for($i=3;$i<=$count;$i++){
		
			if($i%2!=0){
				$time_work+=strtotime($arr_time[$i])-strtotime($arr_time[$i-1]);
			}
		}
		
		//$time_work;        //рабочее время в секундах и оно же время недоступности услуги за месяц Тн. Тоесть это время,которое потратили сапорты на устранение проблемы
	
	
		//floor($time_work/86400) .' day </br>';  //рабочее время в днях
	
		//floor($time_work/3600) .' hour </br>';  //рабочее время в часах
	
		//floor(($time_work%3600)/60) .' minutes </br>'; //рабочее время в минутах
		//($time_work%3600)%60 .' seconds </br>';   //рабочее время в секундах
		
	 
		//записываем рабочее время тикета в массив
	 
		//$arr_all_ticket['time_work']=floor($time_work/86400) .' day '.floor(($time_work%86400)/3600) .' hour '.floor(($time_work%3600)/60) .' minutes '.($time_work%3600)%60 .' seconds ';
		//$arr_all_ticket['time_work']=round($time_work/60).' minutes ';
		
		$arr_all_ticket['time_work']=$time_work;
		
		
		//находим общее время жизни тикета
		
		$total_time=strtotime($arr_time[$count-1])-strtotime($arr_time[0]);//.'</br>';  //общее время жизни тикета
	
		//floor($total_time/86400) .' day </br>';  //рабочее время в днях
	
		//floor($total_time/3600) .' hour </br>';  //рабочее время в часах
	
		//floor(($total_time%3600)/60) .' minutes </br>'; //рабочее время в минутах
		//($total_time%3600)%60 .' seconds </br>';   //рабочее время в секундах
		
		//$arr_all_ticket['total_time']=floor($total_time/86400) .' day '. floor(($total_time%86400)/3600) .' hour '. floor(($total_time%3600)/60) .' minutes'. ($total_time%3600)%60 .' seconds';
	
		$arr_all_ticket['total_time']=$total_time;
		
	
		//переформатируем время начала месяца в формат для mktime
	
		$arr_for_mk1=explode('-',$arr_time[0]);
		$arr_for_mk2=explode(' ',$arr_for_mk1[2]);
		
	
		//echo(mktime(0,0,0,$arr_for_mk1[1],$arr_for_mk2[0],$arr_for_mk1[0]));
		
		
		$days_in_month=date('t',mktime(0,0,0,$arr_for_mk1[1],$arr_for_mk2[0],$arr_for_mk1[0]));  //количество дней в указанном месяце
	
		$time_available='';          //время доступности услуги за месяц Тдн
	
		//находим время остановки тикета по вине клиента
	
		$time_hold=$total_time - $time_work;  //время остановки по вине клиента
	
		//$time_hold;     //время остановки по вине клиента
	
		//floor($time_hold/86400) .' day </br>';  //рабочее время в днях
	
		//floor($time_hold/3600) .' hour </br>';  //рабочее время в часах
	
		//floor(($time_hold%3600)/60) .' minutes </br>'; //рабочее время в минутах
		//($time_hold%3600)%60 .' seconds ';   //рабочее время в секундах
	 
		//$arr_all_ticket['time_hold']=floor($time_hold/86400) .' day '.floor(($time_hold%86400)/3600) .' hour '.floor(($time_hold%3600)/60) .' minutes '.($time_hold%3600)%60 .' seconds ';

		$arr_all_ticket['time_hold']=$time_hold;
		
	
	    //считаем sla , использываем функцию sla
		$arr_all_ticket['sla']=sla($days_in_month,$time_work);
	 
		//echo $arr_all_ticket['ticket_number'].' '.$arr_all_ticket['name'].' '.$arr_all_ticket['subject'].' '.$arr_all_ticket['date'].' '.$arr_all_ticket['time_hold'].' '.$arr_all_ticket['sla'].'</br>';
	 
	
			$result3=[];
	
		
			
			$result3[]=$arr_all_ticket['time_work'];
			$result3[]=$arr_all_ticket['time_hold'];
			$result3[]=$arr_all_ticket['total_time'];
			$result3[]=$arr_all_ticket['sla'];
			//$result3[]=$tag;
			
			return $result3;
			
		}
		
		//функция возвращает логи тикета;
		
		
		function ticketLog($arr){
			
			$result5='';
			
			foreach($arr as $elem){
				
				$result5.=explode('"',$elem['action'])[1];
				
			}
			
			return $result5;
			
		}
		
		//функция нахождения значения последнего ключа в массиве;
		
		function lastKey($arr_time){			
			
			$result=array_keys($arr_time);
			
			return $result[count($result)-1];
			
		}
		
		// функция sla для расчета эффективности работы по формуле Виктора Цимбала
			
		function sla($days_in_month,$time_work){
		
			$days_in_month=(int)$days_in_month*24*60*60;
	
			return round((($days_in_month - $time_work)/$days_in_month*100),2);
		}
		
		//функция возращает имя компаниии по id клиента
		
		function getCompanyName($id){
			
			global $conn;
			
			$request=$conn->query("SELECT companyname,parent_id FROM table WHERE id='$id'");
			$result=$request->fetch_array(MYSQLI_ASSOC);
			
			if(empty($result['companyname'])){
				
				$num=$result['parent_id'];
				$request2=$conn->query("SELECT companyname FROM table WHERE id='$num'");
				$result2=$request2->fetch_array(MYSQLI_ASSOC);
				
				$arr=[];
				$arr[]=$result2['companyname'];
				$arr[]=$result['parent_id'];
				$arr[]=$id;
				
				return $arr;
				
				//return $result2['companyname'];
				//return getCompanyName($result['parent_id']);
				
			}else{
				
				$arr=[];
				$arr[]=$result['companyname'];
				$arr[]=$id;
				$arr[]='';
				
				return $arr;
				
				//return $result['companyname'];
				
			}
			
		}
		
		//Функция проверяет относится ли работник к департаменту сапорт
		
		function checkStaff($employee){
			
			global $conn;
			
			$query=$conn->query("SELECT table FROM table
			LEFT JOIN table ON table=table
			WHERE table='$employee'");
			
			$arr=$query->fetch_all(MYSQLI_ASSOC);
			
			if($arr[0]['team_id']==4){
				
				return true;
			}
			
			return false;
			
		}
		
		function staff_closed($ticket_number){
			
			global $conn;
			
			$query=$conn->query("SELECT action FROM table WHERE ticket_number='$ticket_number' AND action LIKE '%Closed%'");
			$arr=$query->fetch_all(MYSQLI_ASSOC);
			
			$key=count($arr)-1;  //находим ключ последнего элемента массива на тот случай если тикет будет закрыт не один раз
			
			$result=explode(' ',$arr[$key]['action']);
			
			$employee=$result[count($result)-1];  //имя сотрудника
					
					//проверяем что он саппорт
					if(checkStaff($employee)){
						
						return $employee;
					}
			
			//return $result[count($result)-1]; //имя находится в последнем элементе массива
			
		}
		
		function getCategoryAndName($ticket_number){
			
			global $conn;
			
			$query=$conn->query("SELECT table,table,table,
								table as cat_name,
								table
								FROM table 
								LEFT JOIN table ON  table=table
								LEFT JOIN table ON table=table
								LEFT JOIN table ON table=table
								LEFT JOIN table ON table=table
								WHERE table='$ticket_number'");

			$arr=$query->fetch_all(MYSQLI_ASSOC);
			
			return $arr;
			
		}
		
		function staff_depart($ticket_number){
			
			global $conn;
			
			$query=$conn->query("SELECT table,table FROM table
			WHERE ticket_number='$ticket_number' AND (action LIKE '%Ticket department changed to:%' OR action LIKE '%Ticket assignment changed to #%')");
			
			$arr=$query->fetch_all(MYSQLI_ASSOC);
			$count=count($arr);
			$arrSupport=[];//сюда запишем сотрудников Support
			
			$arrResult=[];
			
			$subSup1=explode('"',$arr[0]['action'])[0];
			$subSup2=explode('#',$subSup1)[1];
			$support=preg_split('#[\(\)]#',$subSup2)[1];
			$arrSupport[]=preg_split('#[\(\)]#',$subSup2)[1];
			$arrResult[]=$arrSupport[0];
			
			$arrNoc=[];//сюда запишем сотрудников NOC
			$arrExpl=[];//сюда запишем сотрудников Explotation
			
			$startNocTime=[];
			$stopNocTime=[];
			
			$startExplTime=[];
			$stopExplTime=[];
			
			
			if($count>7){
				//первый и последний елемент массива это привязка  к саппорт(нас не интересует)
				for($k=1;$k<($count-1);$k+=2){
					
					if(preg_match('#("NOC")#',$arr[$k]['action'])){
						
						//$arrNoc[$arr[$k]]=$arr[$k+1];
						//array_push($arrNoc,$arr[$k+1]);
						$startNocTime[]=$arr[$k]['date'];
						$stopNocTime[]=$arr[$k+2]['date'];
						$key=explode('"',$arr[$k]['action'])[1];
						$sub1=explode('"',$arr[$k+1]['action'])[0];
						$sub2=explode('#',$sub1)[1];
						$arrNoc[]=preg_split('#[\(\)]#',$sub2)[1];
					}
					if(preg_match('#("Explotation")#',$arr[$k]['action'])){
						
						//$arrNoc[$arr[$k]]=$arr[$k+1];
						//array_push($arrNoc,$arr[$k+1]);
						$startExplTime[]=$arr[$k]['date'];
						$stopExplTime[]=$arr[$k+2]['date'];
						$key=explode('"',$arr[$k]['action'])[1];
						$sub1=explode('"',$arr[$k+1]['action'])[0];
						$sub2=explode('#',$sub1)[1];
						$arrExpl[]=preg_split('#[\(\)]#',$sub2)[1];
					}
				}
			}
			
			
			if($count<=7){
				//первый и последний елемент массива это привязка  к саппорт(нас не интересует)
				for($k=1;$k<($count-1);$k+=2){
					
					if(preg_match('#("NOC")#',$arr[$k]['action'])){
						
						$startNocTime[]=$arr[$k]['date'];  //врема старта NOC
						$stopNocTime[]=$arr[$k+2]['date']; //время остановки NOC
						$key=explode('"',$arr[$k]['action'])[1];
						$sub1=explode('"',$arr[$k+1]['action'])[0];
						$sub2=explode('#',$sub1)[1];
						$sub3=str_replace(['(',')'],'',$sub2);
						$arrNoc[]=preg_split('#[\(\)]#',$sub2)[1];
					
					}
					
					if(preg_match('#("Explotation")#',$arr[$k]['action'])){
						
						$startExplTime[]=$arr[$k]['date'];
						$stopExplTime[]=$arr[$k+2]['date'];
						$key=explode('"',$arr[$k]['action'])[1];
						$sub1=explode('"',$arr[$k+1]['action'])[0];
						$sub2=explode('#',$sub1)[1];
						$arrExpl[]=preg_split('#[\(\)]#',$sub2)[1];
					}
					
					
				}
				
			}
			
			
			$arrResult[]=$arrNoc[0];  //имя и фамилия 
			$arrResult[]=$arrExpl[0];//имя и фамилия
			
			//var_dump($startNocTime);
			//echo '<br>';
			
			//var_dump($startExplTime);
			//echo '<br>';
			
			if(!empty($startNocTime)){
				
				$arrNocTime_work=0;//рабочее время Noc
				
				for($i=0;$i<count($startNocTime);$i++){
					
					$queryNoc=$conn->query("SELECT*FROM table WHERE ticket_number='$ticket_number' AND date>'$startNocTime[$i]' AND date<'$stopNocTime[$i]'");
					$arrNocTimeLog=$queryNoc->fetch_all(MYSQLI_ASSOC); //массив логов для вычисления времени сотрудника NOC
					$arrNocTime[]=$startNocTime[$i];
					
					foreach($arrNocTimeLog as $elem1){
				
						if(preg_match('#\\sfrom\\s(("Open")|("Client-Reply")|("Answered")|("In-Progress"))\\sto\\s(("In-Progress")|("Hold")|("Done")|("Answered")|("Closed"))\\s#',$elem1['action'])){
				
							//echo lastKey($arr_time).'</br>';
							if(lastKey($arrNocTime)===0){
					
								$arrNocTime[]=$elem1['date'];
					
							}
							if(lastKey($arrNocTime)!==0 && lastKey($arrNocTime)%2 === 0){
					
								$arrNocTime[]=$elem1['date'];
								//echo $elem['date'].' Время остановлено</br>';
							} 
				
				
							if(lastKey($arrNocTime)!==0 && lastKey($arrNocTime)%2 !==0){
					
								$arrNocTime[lastKey($arrNocTime)]=$elem1['date'];
					
							}
				
				
						}
						if(preg_match('#\\sfrom\\s(("Hold")|("Done")|("In-Progress")|("Answered")|("Closed"))\\sto\\s(("Client-Reply")|("Open"))\\s#',$elem1['action'])){
			
							//echo lastKey($arr_time).'</br>';
			
							if(lastKey($arrNocTime)!==0 && lastKey($arrNocTime)%2 !==0){
					
								$arrNocTime[]=$elem1['date'];
					
							}
							if(lastKey($arrNocTime)!==0 && lastKey($arrNocTime)%2 === 0){
					
								$arrNocTime[lastKey($arrNocTime)]=$elem1['date'];
								//echo $elem['date'].' Время остановлено</br>';
							} 
			
							//$arr_time[]=$elem['date'];
							//echo $elem['date'].' Идет учет</br>';
			
						}
					
					
					}
					
					if(count($arrNocTime)%2!=0){
						
						$arrNocTime[]=$stopNocTime[0];
						
					}
					
					$count1=count($arrNocTime);
					
					//echo $count1.'<br>';
					
					//var_dump($arrNocTime);
					
					//echo '<br>';
		
					$arrNocTime_work+=strtotime($arrNocTime[1])-strtotime($arrNocTime[0]);
		
						//if($count>3){
							for($i=3;$i<$count1;$i++){
		
								if($i%2!=0){
			
									$arrNocTime_work+=strtotime($arrNocTime[$i])-strtotime($arrNocTime[$i-1]);
								}
							}
						//}
				
			
					//return $arrResult;
			
					//return $arrNocTime_work;
				
				}
				//return $arrNocTime_work;
				
				$arrResult[]=$arrNocTime_work;
				
				//var_dump($arrNocTime_work);
				
				//echo '<br>';
					
			}
			
			if(!empty($startExplTime)){
				
				$arrExplTime_work=0;//рабочее время Noc
				
				for($i=0;$i<count($startExplTime);$i++){
					
					$queryExpl=$conn->query("SELECT*FROM table WHERE ticket_number='$ticket_number' AND date>'$startExplTime[$i]' AND date<'$stopExplTime[$i]'");
					$arrExplTimeLog=$queryExpl->fetch_all(MYSQLI_ASSOC); //массив логов для вычисления времени сотрудника Expl
					$arrExplTime[]=$startExplTime[$i];
					
					foreach($arrExplTimeLog as $elem1){
				
						if(preg_match('#\\sfrom\\s(("Open")|("Client-Reply")|("Answered")|("In-Progress"))\\sto\\s(("In-Progress")|("Hold")|("Done")|("Answered")|("Closed"))\\s#',$elem1['action'])){
				
							//echo lastKey($arr_time).'</br>';
							if(lastKey($arrExplTime)===0){
					
								$arrExplTime[]=$elem1['date'];
					
							}
							if(lastKey($arrExplTime)!==0 && lastKey($arrExplTime)%2 === 0){
					
								$arrExplTime[]=$elem1['date'];
								//echo $elem['date'].' Время остановлено</br>';
							} 
				
				
							if(lastKey($arrExplTime)!==0 && lastKey($arrExplTime)%2 !==0){
					
								$arrExplTime[lastKey($arrExplTime)]=$elem1['date'];
					
							}
				
				
						}
						if(preg_match('#\\sfrom\\s(("Hold")|("Done")|("In-Progress")|("Answered")|("Closed"))\\sto\\s(("Client-Reply")|("Open"))\\s#',$elem1['action'])){
			
							//echo lastKey($arr_time).'</br>';
			
							if(lastKey($arrExplTime)!==0 && lastKey($arrExplTime)%2 !==0){
					
								$arrExplTime[]=$elem1['date'];
					
							}
							if(lastKey($arrExplTime)!==0 && lastKey($arrExplTime)%2 === 0){
					
								$arrExplTime[lastKey($arrExplTime)]=$elem1['date'];
								//echo $elem['date'].' Время остановлено</br>';
							} 
			
							//$arr_time[]=$elem['date'];
							//echo $elem['date'].' Идет учет</br>';
			
						}
					
					
					}
					
					if(count($arrExplTime)%2!=0){
						
						$arrExplTime[]=$stopExplTime[0];
						
					}
					
					$count2=count($arrExplTime);
					
					//echo $count2.'<br>';
					
					//var_dump($arrExplTime);
					
					//echo '<br>';
		
					$arrExplTime_work+=strtotime($arrExplTime[1])-strtotime($arrExplTime[0]);
		
						
							for($i=3;$i<$count2;$i++){
		
								if($i%2!=0){
			
									$arrExplTime_work+=strtotime($arrExplTime[$i])-strtotime($arrExplTime[$i-1]);
								}
							}
						
				
			
					//return $arrResult;
			
					//return $arrNocTime_work;
				
				}
				//return $arrNocTime_work;
				
				$arrResult[]=$arrExplTime_work;
				
				//var_dump($arrExplTime_work);
				
				//echo '<br>';
					
			}
			
			return $arrResult;
			
		}
		
	?>
	<?php
	
	
	//для пагинации
	
	/*
	for($i=1;$i<=$pageCount;$i++){
		
			if($page==$i){
				
				echo "<a href=\"?ticket_form=&submit=Відправити&page=$i\" class=\"active\">$i </a>";
			}else{
				
				echo "<a href=\"?ticket_form=&submit=Відправити&page=$i\">$i </a>";
			}
	
		}
	*/
	/*
	for($i=1;$i<=$pageCount;$i++){
		
			if($page==$i&!empty($_GET['ticket_form'])&!isset($_GET['ticket_form_start'])&!isset($_GET['ticket_form_end'])){
				
				echo "<a href=\"?ticket_form=$date_start&submit=Відправити&page=$i\" class=\"active\">$i </a>";
				
			}elseif($page==$i&!empty($_GET['ticket_form_start'])&!empty($_GET['ticket_form_end'])&!isset($_GET['ticket_form'])){
				
				echo "<a href=\"?ticket_form_start=$date_start&ticket_form_end=$date_end&submit=Відправити&page=$i\" class=\"active\">$i </a>";
				
			}else{
				
				if(!empty($_GET['ticket_form'])&!isset($_GET['ticket_form_start'])&!isset($_GET['ticket_form_end'])){
					
				echo "<a href=\"?ticket_form=$date_start&submit=Відправити&page=$i\">$i </a>";
				
				}
				
				elseif(!empty($_GET['ticket_form_start'])&!empty($_GET['ticket_form_end'])&!isset($_GET['ticket_form'])){
					
					$date=date_create($_GET['ticket_form_end']);
					date_modify($date,'1 second');
					$date_end=date_format($date,'Y-m-d'); 
					
				echo "<a href=\"?ticket_form_start=$date_start&ticket_form_end=$date_end&submit=Відправити&page=$i\">$i </a>";
				
				}
			}
		}
		*/
	
	?>