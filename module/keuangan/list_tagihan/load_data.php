<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	if($_SESSION['outlet'] == 90000){
		$outlet = '';
	} else {
		$outlet = $_SESSION['outlet'];
	}
	
	$rs = mysqli_query($con,"select count(*) from fin_trn_inv_hdr a
		join infinity.titik b on a.invhdr_segment1=b.kode_titik
		join mst_rekening c on a.invhdr_supplier=c.rek_cs
		join mst_bank d on c.rek_bank=d.bank_id");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
			invhdr_no,
			nama_titik,
			invhdr_amount,
			bank_name,
			invhdr_rek_no,
			case 
				when invhdr_status='R' then 'REQUEST'
				when invhdr_status='A' then 'APPROVE'
				when invhdr_status='C' then 'CANCEL'
				when invhdr_status='J' then 'REJECT'
				else 'PAID'
			end as invhdr_status
		from fin_trn_inv_hdr a
		join infinity.titik b on a.invhdr_segment1=b.kode_titik
		join mst_rekening c on a.invhdr_rek_no=c.rek_no
		join mst_bank d on c.rek_bank=d.bank_id
		where invhdr_segment1 like '%$outlet%'
		order by invhdr_no desc");
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>