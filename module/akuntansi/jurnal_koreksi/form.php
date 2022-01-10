<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

$rs = mysqli_query($con,"select * from 
	(
		(select
			gl_segment1 as kode_titik,
			nama_titik,
			case
				when gl_segment2='112010134' then 'BANK'
				else 'LAINNYA'
			end as jc_type,
			gl_segment2 as coa_code,
			coa_description,
			(select corr_reff_no from fin_trn_correct where corr_no='$id' and gl_segment2='3310201') as invhdr_reff_no,
			gl_dr,
			gl_cr
		from gl_journal a
		join infinity.titik b on a.gl_segment1=b.kode_titik
		join gl_coa c on a.gl_segment2=c.coa_code
		where gl_no='$id'
		order by gl_id asc limit 0,1)as r1,
		(select
			gl_segment1 as kode_titik2,
			nama_titik as nama_titik2,
			case
				when gl_segment2='112010134' then 'BANK'
				else 'LAINNYA'
			end as jc_type2,
			gl_segment2 as coa_code2,
			coa_description as coa_description2,
			(select corr_reff_no from fin_trn_correct where corr_no='$id' and gl_segment2='3310201') as invhdr_reff_no2,
			gl_dr as gl_dr2,
			gl_cr as gl_cr2
		from gl_journal a
		join infinity.titik b on a.gl_segment1=b.kode_titik
		join gl_coa c on a.gl_segment2=c.coa_code
		where gl_no='$id'
		order by gl_id desc limit 0,1) as r2
	)");

$row = mysqli_fetch_object($rs);

if($row != null){
	echo json_encode($row);
}
?>