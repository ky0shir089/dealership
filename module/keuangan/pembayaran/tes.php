<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$query4 = "select * from repayment_rv where rrv_pln_id='106331705PLN00006'";
$hasil4 = mysqli_query($con,$query4) or die(mysqli_error($con));
while($data4 = mysqli_fetch_array($hasil4)){
	$rrv_pln_id = $data4['rrv_pln_id'];
	$rrv_no_rv = $data4['rrv_no_rv'];
	$rrv_amount_rv = $data4['rrv_amount_rv'];
	$rrv_amount_pv = $data4['rrv_amount_pv'];
	
	$query8 = "select pv_rv_amount from fin_trn_payment where pv_pln_no='106331705PLN00006'";
	$hasil8 = mysqli_query($con,$query8);
	$data8 = mysqli_fetch_array($hasil8);
	$pv_amount = $data8['pv_rv_amount'];
	$selisih = $pv_amount-$rrv_amount_rv;
	if($pv_amount > $rrv_amount_rv){
		$status = "true";
		
		$query6 = "update fin_trn_payment set pv_rv_amount='$selisih' where pv_pln_no='106331705PLN00006'";
		$result6 = mysqli_query($con,$query6);
	} else {
		$status = "false";
	}
?>
<table>
	<tr>
		<td><?= $rrv_pln_id; ?></td>
		<td><?= $rrv_no_rv; ?></td>
		<td><?= $rrv_amount_rv; ?></td>
		<td><?= $status; ?></td>
	</tr>
</table>
<?php } ?>