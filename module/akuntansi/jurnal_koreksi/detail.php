<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

$query = "select
			gl_segment1 as kode_titik,
			nama_titik,
			(case
				when coa_parent like '%1120%' then 'BANK'
				else 'LAINNYA'
			end) as jc_type,
			gl_segment2 as coa_code,
			coa_description,
			corr_reff_no as invhdr_reff_no,
			gl_dr,
			gl_cr
		from gl_journal a
		join infinity.titik b on a.gl_segment1=b.kode_titik
		join gl_coa c on a.gl_segment2=c.coa_code
		left join fin_trn_correct d on a.gl_no=d.corr_no
		where gl_no='$id'";
$rs = mysqli_query($con,$query);
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>