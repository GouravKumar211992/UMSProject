@extends("ums.admin.admin-meta")
@section("content")

{{-- <!DOCTYPE html>
<html lang="en"><head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student::Icards</title> --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="/assets/admin/css/style_icard.css" rel="stylesheet">
    <style>
        .first_page_container{
            width:57mm;
            float:left;
        }
        .ecard-container{
            width:250mm;
            margin:auto;
        }
    .header_section{
        background-color: #ff7703;
        border-radius: 5px 5px 0px 0px;
        background-image : url("http://64.227.161.177/images/logo.png");
        background-position: 4px;
        background-repeat: no-repeat;
        background-size: 35px;
        padding-top: 3px;
        /*padding-left: 25px;*/
    }
	.header_section p {
		margin:0px;
		font-size:8px;
	}
    .header_section h6{
		font-size: 10px;
        margin-bottom: 3px;
    }
    .photo_here{
        border: black thin solid;
        padding: 0px;
        width: 65px;
        height: 63px;
        margin-left: -40px;
        border-radius: 5px;
		margin-top: -7px;
    }
    .photo_here img{
		height: 61px !important;
        width: 96% !important;
	}
    .first_page{
        width: 55mm;
        height: 88mm;
        margin-top: 10mm;
        border: 1px #000 solid;
        border-radius: 5px;
    }
    .sign{
        text-align: center;
        width: 100px;
        float: right;
        font-size: 10px;
        font-weight: 600;
        
    }
    .sign_2{
        font-size: 10px;
        display:block;
    }
    .qr_table{
        width: 100%;
        font-size: 8px;
        padding: 5px;
        margin-top: 5px;
    }
    .qr_table td{
        padding: 0px 5px;
    }
	@media  print {
		body {-webkit-print-color-adjust: exact;}
		body
		{
		  /*margin: 0 100px 0 0;*/
		}
		.pagination,
		.print_hide{
			display:none;
		}
        @page  {size: landscape;}
        .page_break {page-break-after: always;}
	}
    </style>
    {{-- </head> --}}
{{-- <body>
    @include("header")
    @include("sidebar") --}}
<div class="containers">

<div class="row print_hide">
	<div class="col-md-12 text-center">
		<button onclick="window.print()" class="btn btn-primary" style="margin-top: 10px;width: 12%;font-size: 17px;font-weight: 700;">Print</button>
	</div>
</div>

<div class="ecard-container ">


<div class="page_break">
<div style="height:5px;width:100%;clear:both;" class="clearfix">&nbsp;</div>

<div class="first_page_container">

    <div class="first_page">
        <div class="header_section">
        <div class="row">
            <div class="col-md-2">
                <img src="http://64.227.161.177/assets/frontend/images/icon.png" style="height:45px;width:45px; margin-top: 10px; margin-left: 1px;">
            </div>
            <div class="col-md-10 text-white text-center">
                <h6>Dr. Shakuntala Misra<br>National Rehabilitation University</h6>
                <!--p>Govt. of Uttar Pradesh</p-->
                <p>Mohaan Road, Lucknow U.P. 226017</p>
                <p>Toll Free No. : 1800 180 0987</p>
                <p style="padding-bottom:8px">Website : https://dsmru.up.nic.in/</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-xs-9 col-9">
            <br><table style="width: 55mm;strong;font-size: 8px;margin-top:-18px;">
                <tbody><tr>
                    <td style="vertical-align: top; padding-left: 10px;"><strong style="font-weight: 600;">ID Card</strong> : SU1800000413</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Validity</strong> : Academic</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Name</strong> : AMIT SHARMA </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Date of Birth</strong> : 08/03/2000</td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Father's Name</strong> : TEJPAL SHARMA</td>
                    <td style="padding-left: 10px;"></td>
				</tr>
				<tr>

                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Course</strong> : B.Com.</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                    <!--tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Subjects</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr--> 
                
                
                    <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Disability If any</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Gender</strong> : male</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Student's Contact No</strong> : 9956332293</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Nationality</strong> : Indian </td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Blood Group</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Mailing Address</strong> : <span style="font-size:8px;"></span></td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Local Guardian's Name</strong> :   </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Guardian's Contact No</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            </tbody></table>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3 col-3 mt-2">
			<div class="photo_here">
                				<p style="text-align: center">
                                        <img src="https://thebasmatihouse.com/storage/44145/P.jpg">
                                    </p>
			</div>
		</div>
    </div>
    <div class="row">
        <div class="col-md-8 mt-2">
            <table style="width: 400px;font-size:15px;margin-top: -10px;">
               
            </table> 
        </div>
    </div>
  
</div>  


<div class="first_page">
        <div class="row ">
            <div class="col-md-12">
				<h4 style="text-align: center;margin-top: 5px;font-size: 12px;">Instructions</h4>
                <ol style="padding-left: 14px;font-size:8px;padding-right: 5px;margin:0px;">
                    <li>This identity card is non-transferable</li>
                    <li>Students must carry this card in this University Campus and are required to show on demand.</li>
                    <li>Misuse of the card is thus liable for punishment as per the University rules.</li>
                    <li>In case of loss / damage of this card Rs. 200/- will be charged for Issuing duplicate card.</li>
                </ol>

                <table class="qr_table">
                    <tbody><tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;Issued on : 27/11/2021</b>
                    </td></tr>
                    <tr>
					                        <td style="text-align:center;">
                            <img src="https://chart.googleapis.com/chart?chs=300x300&amp;cht=qr&amp;chl=ID Card : SU1800000413 Name : AMIT SHARMA Contact : 9956332293&amp;choe=UTF-8" style="width:70px;height:70px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 50px;">
							<div class="sign"><img src="http://64.227.161.177/signatures/vk_singh.png" style="width:100%;height:auto;"></div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 0px;">
							<div class="sign" style="margin-top: -5px;"><span class="sign_2">( Prof. V. K. Singh )</span>Proctor </div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:left;padding-top:5px;">
                            <strong>Note :</strong> This card is property of the University. If found misplaced, please return to the office of the issuing authority.
                        </td>
                    </tr>
                </tbody></table>
                
        
                
    
    
    
            </div>
        </div>

    </div>

</div>






<div class="first_page_container">

    <div class="first_page">
        <div class="header_section">
        <div class="row">
            <div class="col-md-2">
                <img src="http://64.227.161.177/assets/frontend/images/icon.png" style="height:45px;width:45px; margin-top: 10px; margin-left: 1px;">
            </div>
            <div class="col-md-10 text-white text-center">
                <h6>Dr. Shakuntala Misra<br>National Rehabilitation University</h6>
                <!--p>Govt. of Uttar Pradesh</p-->
                <p>Mohaan Road, Lucknow U.P. 226017</p>
                <p>Toll Free No. : 1800 180 0987</p>
                <p style="padding-bottom:8px">Website : https://dsmru.up.nic.in/</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-xs-9 col-9">
            <br><table style="width: 55mm;strong;font-size: 8px;margin-top:-18px;">
                <tbody><tr>
                    <td style="vertical-align: top; padding-left: 10px;"><strong style="font-weight: 600;">ID Card</strong> : SU2000000351</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Validity</strong> : Academic</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Name</strong> : SUJAL ASHISH </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Date of Birth</strong> : 26/12/2002</td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Father's Name</strong> : ASHISH KUMAR</td>
                    <td style="padding-left: 10px;"></td>
				</tr>
				<tr>

                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Course</strong> : B.Com.</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                    <!--tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Subjects</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr--> 
                
                
                    <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Disability If any</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Gender</strong> : male</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Student's Contact No</strong> : 7618072387</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Nationality</strong> : Indian </td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Blood Group</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Mailing Address</strong> : <span style="font-size:8px;"></span></td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Local Guardian's Name</strong> :   </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Guardian's Contact No</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            </tbody></table>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3 col-3 mt-2">
			<div class="photo_here">
                				<p style="text-align: center">
                                        <img src="https://thebasmatihouse.com/storage/69818/IMG-20230104-WA0166.jpg">
                                    </p>
			</div>
		</div>
    </div>
    <div class="row">
        <div class="col-md-8 mt-2">
            <table style="width: 400px;font-size:15px;margin-top: -10px;">
               
            </table> 
        </div>
    </div>
  
</div>  


<div class="first_page">
        <div class="row ">
            <div class="col-md-12">
				<h4 style="text-align: center;margin-top: 5px;font-size: 12px;">Instructions</h4>
                <ol style="padding-left: 14px;font-size:8px;padding-right: 5px;margin:0px;">
                    <li>This identity card is non-transferable</li>
                    <li>Students must carry this card in this University Campus and are required to show on demand.</li>
                    <li>Misuse of the card is thus liable for punishment as per the University rules.</li>
                    <li>In case of loss / damage of this card Rs. 200/- will be charged for Issuing duplicate card.</li>
                </ol>

                <table class="qr_table">
                    <tbody><tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;Issued on : 27/11/2021</b>
                    </td></tr>
                    <tr>
					                        <td style="text-align:center;">
                            <img src="https://chart.googleapis.com/chart?chs=300x300&amp;cht=qr&amp;chl=ID Card : SU2000000351 Name : SUJAL ASHISH Contact : 7618072387&amp;choe=UTF-8" style="width:70px;height:70px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 50px;">
							<div class="sign"><img src="http://64.227.161.177/signatures/vk_singh.png" style="width:100%;height:auto;"></div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 0px;">
							<div class="sign" style="margin-top: -5px;"><span class="sign_2">( Prof. V. K. Singh )</span>Proctor </div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:left;padding-top:5px;">
                            <strong>Note :</strong> This card is property of the University. If found misplaced, please return to the office of the issuing authority.
                        </td>
                    </tr>
                </tbody></table>
                
        
                
    
    
    
            </div>
        </div>

    </div>

</div>






<div class="first_page_container">

    <div class="first_page">
        <div class="header_section">
        <div class="row">
            <div class="col-md-2">
                <img src="http://64.227.161.177/assets/frontend/images/icon.png" style="height:45px;width:45px; margin-top: 10px; margin-left: 1px;">
            </div>
            <div class="col-md-10 text-white text-center">
                <h6>Dr. Shakuntala Misra<br>National Rehabilitation University</h6>
                <!--p>Govt. of Uttar Pradesh</p-->
                <p>Mohaan Road, Lucknow U.P. 226017</p>
                <p>Toll Free No. : 1800 180 0987</p>
                <p style="padding-bottom:8px">Website : https://dsmru.up.nic.in/</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-xs-9 col-9">
            <br><table style="width: 55mm;strong;font-size: 8px;margin-top:-18px;">
                <tbody><tr>
                    <td style="vertical-align: top; padding-left: 10px;"><strong style="font-weight: 600;">ID Card</strong> : SU2000000122</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Validity</strong> : Academic</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Name</strong> : PRADEEP KUMAR </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Date of Birth</strong> : 01/07/2001</td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Father's Name</strong> : RAM NAWAL</td>
                    <td style="padding-left: 10px;"></td>
				</tr>
				<tr>

                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Course</strong> : B.TECH (CE)</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                    <!--tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Subjects</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr--> 
                
                
                    <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Disability If any</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Gender</strong> : male</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Student's Contact No</strong> : 9005275626</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Nationality</strong> : Indian </td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Blood Group</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Mailing Address</strong> : <span style="font-size:8px;"></span></td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Local Guardian's Name</strong> :   </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Guardian's Contact No</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            </tbody></table>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3 col-3 mt-2">
			<div class="photo_here">
                				<p style="text-align: center">
                                        <img src="https://thebasmatihouse.com/storage/2463/IMG_20220212_084951_118.jpg">
                                    </p>
			</div>
		</div>
    </div>
    <div class="row">
        <div class="col-md-8 mt-2">
            <table style="width: 400px;font-size:15px;margin-top: -10px;">
               
            </table> 
        </div>
    </div>
  
</div>  


<div class="first_page">
        <div class="row ">
            <div class="col-md-12">
				<h4 style="text-align: center;margin-top: 5px;font-size: 12px;">Instructions</h4>
                <ol style="padding-left: 14px;font-size:8px;padding-right: 5px;margin:0px;">
                    <li>This identity card is non-transferable</li>
                    <li>Students must carry this card in this University Campus and are required to show on demand.</li>
                    <li>Misuse of the card is thus liable for punishment as per the University rules.</li>
                    <li>In case of loss / damage of this card Rs. 200/- will be charged for Issuing duplicate card.</li>
                </ol>

                <table class="qr_table">
                    <tbody><tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;Issued on : 27/11/2021</b>
                    </td></tr>
                    <tr>
					                        <td style="text-align:center;">
                            <img src="https://chart.googleapis.com/chart?chs=300x300&amp;cht=qr&amp;chl=ID Card : SU2000000122 Name : PRADEEP KUMAR Contact : 9005275626&amp;choe=UTF-8" style="width:70px;height:70px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 50px;">
							<div class="sign"><img src="http://64.227.161.177/signatures/vk_singh.png" style="width:100%;height:auto;"></div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 0px;">
							<div class="sign" style="margin-top: -5px;"><span class="sign_2">( Prof. V. K. Singh )</span>Proctor </div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:left;padding-top:5px;">
                            <strong>Note :</strong> This card is property of the University. If found misplaced, please return to the office of the issuing authority.
                        </td>
                    </tr>
                </tbody></table>
                
        
                
    
    
    
            </div>
        </div>

    </div>

</div>






<div class="first_page_container">

    <div class="first_page">
        <div class="header_section">
        <div class="row">
            <div class="col-md-2">
                <img src="http://64.227.161.177/assets/frontend/images/icon.png" style="height:45px;width:45px; margin-top: 10px; margin-left: 1px;">
            </div>
            <div class="col-md-10 text-white text-center">
                <h6>Dr. Shakuntala Misra<br>National Rehabilitation University</h6>
                <!--p>Govt. of Uttar Pradesh</p-->
                <p>Mohaan Road, Lucknow U.P. 226017</p>
                <p>Toll Free No. : 1800 180 0987</p>
                <p style="padding-bottom:8px">Website : https://dsmru.up.nic.in/</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-xs-9 col-9">
            <br><table style="width: 55mm;strong;font-size: 8px;margin-top:-18px;">
                <tbody><tr>
                    <td style="vertical-align: top; padding-left: 10px;"><strong style="font-weight: 600;">ID Card</strong> : SU2000000374</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Validity</strong> : Academic</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Name</strong> : HEMENDRA PAL </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Date of Birth</strong> : 10/07/1993</td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Father's Name</strong> : RAM SHANKER PAL</td>
                    <td style="padding-left: 10px;"></td>
				</tr>
				<tr>

                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Course</strong> : BVA</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                    <!--tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Subjects</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr--> 
                
                
                    <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Disability If any</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Gender</strong> : male</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Student's Contact No</strong> : 7505666140</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Nationality</strong> : Indian </td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Blood Group</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Mailing Address</strong> : <span style="font-size:8px;"></span></td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Local Guardian's Name</strong> :   </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Guardian's Contact No</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            </tbody></table>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3 col-3 mt-2">
			<div class="photo_here">
                				<p style="text-align: center">
                                        <img src="https://thebasmatihouse.com/storage/4920/WhatsApp-Image-2022-02-14-at-12.27.59-PM-%281%29.jpeg">
                                    </p>
			</div>
		</div>
    </div>
    <div class="row">
        <div class="col-md-8 mt-2">
            <table style="width: 400px;font-size:15px;margin-top: -10px;">
               
            </table> 
        </div>
    </div>
  
</div>  


<div class="first_page">
        <div class="row ">
            <div class="col-md-12">
				<h4 style="text-align: center;margin-top: 5px;font-size: 12px;">Instructions</h4>
                <ol style="padding-left: 14px;font-size:8px;padding-right: 5px;margin:0px;">
                    <li>This identity card is non-transferable</li>
                    <li>Students must carry this card in this University Campus and are required to show on demand.</li>
                    <li>Misuse of the card is thus liable for punishment as per the University rules.</li>
                    <li>In case of loss / damage of this card Rs. 200/- will be charged for Issuing duplicate card.</li>
                </ol>

                <table class="qr_table">
                    <tbody><tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;Issued on : 27/11/2021</b>
                    </td></tr>
                    <tr>
					                        <td style="text-align:center;">
                            <img src="https://chart.googleapis.com/chart?chs=300x300&amp;cht=qr&amp;chl=ID Card : SU2000000374 Name : HEMENDRA PAL Contact : 7505666140&amp;choe=UTF-8" style="width:70px;height:70px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 50px;">
							<div class="sign"><img src="http://64.227.161.177/signatures/vk_singh.png" style="width:100%;height:auto;"></div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 0px;">
							<div class="sign" style="margin-top: -5px;"><span class="sign_2">( Prof. V. K. Singh )</span>Proctor </div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:left;padding-top:5px;">
                            <strong>Note :</strong> This card is property of the University. If found misplaced, please return to the office of the issuing authority.
                        </td>
                    </tr>
                </tbody></table>
                
        
                
    
    
    
            </div>
        </div>

    </div>

</div>


</div>



<div class="page_break">
<div style="height:5px;width:100%;clear:both;" class="clearfix">&nbsp;</div>

<div class="first_page_container">

    <div class="first_page">
        <div class="header_section">
        <div class="row">
            <div class="col-md-2">
                <img src="http://64.227.161.177/assets/frontend/images/icon.png" style="height:45px;width:45px; margin-top: 10px; margin-left: 1px;">
            </div>
            <div class="col-md-10 text-white text-center">
                <h6>Dr. Shakuntala Misra<br>National Rehabilitation University</h6>
                <!--p>Govt. of Uttar Pradesh</p-->
                <p>Mohaan Road, Lucknow U.P. 226017</p>
                <p>Toll Free No. : 1800 180 0987</p>
                <p style="padding-bottom:8px">Website : https://dsmru.up.nic.in/</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-xs-9 col-9">
            <br><table style="width: 55mm;strong;font-size: 8px;margin-top:-18px;">
                <tbody><tr>
                    <td style="vertical-align: top; padding-left: 10px;"><strong style="font-weight: 600;">ID Card</strong> : SU2000000163</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Validity</strong> : Academic</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Name</strong> : MOHD FAIZ </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Date of Birth</strong> : 04/04/2003</td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Father's Name</strong> : FAHEEM AHMAD</td>
                    <td style="padding-left: 10px;"></td>
				</tr>
				<tr>

                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Course</strong> : B.TECH (CE)</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                    <!--tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Subjects</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr--> 
                
                
                    <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Disability If any</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Gender</strong> : male</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Student's Contact No</strong> : 7357987609</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Nationality</strong> : Indian </td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Blood Group</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Mailing Address</strong> : <span style="font-size:8px;"></span></td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Local Guardian's Name</strong> :   </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Guardian's Contact No</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            </tbody></table>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3 col-3 mt-2">
			<div class="photo_here">
                				<p style="text-align: center">
                                        <img src="https://thebasmatihouse.com/storage/6161/IMG16448460706890.png">
                                    </p>
			</div>
		</div>
    </div>
    <div class="row">
        <div class="col-md-8 mt-2">
            <table style="width: 400px;font-size:15px;margin-top: -10px;">
               
            </table> 
        </div>
    </div>
  
</div>  


<div class="first_page">
        <div class="row ">
            <div class="col-md-12">
				<h4 style="text-align: center;margin-top: 5px;font-size: 12px;">Instructions</h4>
                <ol style="padding-left: 14px;font-size:8px;padding-right: 5px;margin:0px;">
                    <li>This identity card is non-transferable</li>
                    <li>Students must carry this card in this University Campus and are required to show on demand.</li>
                    <li>Misuse of the card is thus liable for punishment as per the University rules.</li>
                    <li>In case of loss / damage of this card Rs. 200/- will be charged for Issuing duplicate card.</li>
                </ol>

                <table class="qr_table">
                    <tbody><tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;Issued on : 27/11/2021</b>
                    </td></tr>
                    <tr>
					                        <td style="text-align:center;">
                            <img src="https://chart.googleapis.com/chart?chs=300x300&amp;cht=qr&amp;chl=ID Card : SU2000000163 Name : MOHD FAIZ Contact : 7357987609&amp;choe=UTF-8" style="width:70px;height:70px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 50px;">
							<div class="sign"><img src="http://64.227.161.177/signatures/vk_singh.png" style="width:100%;height:auto;"></div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 0px;">
							<div class="sign" style="margin-top: -5px;"><span class="sign_2">( Prof. V. K. Singh )</span>Proctor </div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:left;padding-top:5px;">
                            <strong>Note :</strong> This card is property of the University. If found misplaced, please return to the office of the issuing authority.
                        </td>
                    </tr>
                </tbody></table>
                
        
                
    
    
    
            </div>
        </div>

    </div>

</div>






<div class="first_page_container">

    <div class="first_page">
        <div class="header_section">
        <div class="row">
            <div class="col-md-2">
                <img src="http://64.227.161.177/assets/frontend/images/icon.png" style="height:45px;width:45px; margin-top: 10px; margin-left: 1px;">
            </div>
            <div class="col-md-10 text-white text-center">
                <h6>Dr. Shakuntala Misra<br>National Rehabilitation University</h6>
                <!--p>Govt. of Uttar Pradesh</p-->
                <p>Mohaan Road, Lucknow U.P. 226017</p>
                <p>Toll Free No. : 1800 180 0987</p>
                <p style="padding-bottom:8px">Website : https://dsmru.up.nic.in/</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-xs-9 col-9">
            <br><table style="width: 55mm;strong;font-size: 8px;margin-top:-18px;">
                <tbody><tr>
                    <td style="vertical-align: top; padding-left: 10px;"><strong style="font-weight: 600;">ID Card</strong> : SU2000000319</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Validity</strong> : Academic</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Name</strong> : PANKAJ KUMAR </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Date of Birth</strong> : 07/08/2003</td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Father's Name</strong> : PRAMOD KUMAR</td>
                    <td style="padding-left: 10px;"></td>
				</tr>
				<tr>

                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Course</strong> : B.Com.</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                    <!--tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Subjects</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr--> 
                
                
                    <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Disability If any</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Gender</strong> : male</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Student's Contact No</strong> : 7068397747</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Nationality</strong> : Indian </td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Blood Group</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Mailing Address</strong> : <span style="font-size:8px;"></span></td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Local Guardian's Name</strong> :   </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Guardian's Contact No</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            </tbody></table>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3 col-3 mt-2">
			<div class="photo_here">
                				<p style="text-align: center">
                                        <img src="https://thebasmatihouse.com/storage/8272/PANKAJ-P.jpg">
                                    </p>
			</div>
		</div>
    </div>
    <div class="row">
        <div class="col-md-8 mt-2">
            <table style="width: 400px;font-size:15px;margin-top: -10px;">
               
            </table> 
        </div>
    </div>
  
</div>  


<div class="first_page">
        <div class="row ">
            <div class="col-md-12">
				<h4 style="text-align: center;margin-top: 5px;font-size: 12px;">Instructions</h4>
                <ol style="padding-left: 14px;font-size:8px;padding-right: 5px;margin:0px;">
                    <li>This identity card is non-transferable</li>
                    <li>Students must carry this card in this University Campus and are required to show on demand.</li>
                    <li>Misuse of the card is thus liable for punishment as per the University rules.</li>
                    <li>In case of loss / damage of this card Rs. 200/- will be charged for Issuing duplicate card.</li>
                </ol>

                <table class="qr_table">
                    <tbody><tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;Issued on : 27/11/2021</b>
                    </td></tr>
                    <tr>
					                        <td style="text-align:center;">
                            <img src="https://chart.googleapis.com/chart?chs=300x300&amp;cht=qr&amp;chl=ID Card : SU2000000319 Name : PANKAJ KUMAR Contact : 7068397747&amp;choe=UTF-8" style="width:70px;height:70px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 50px;">
							<div class="sign"><img src="http://64.227.161.177/signatures/vk_singh.png" style="width:100%;height:auto;"></div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 0px;">
							<div class="sign" style="margin-top: -5px;"><span class="sign_2">( Prof. V. K. Singh )</span>Proctor </div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:left;padding-top:5px;">
                            <strong>Note :</strong> This card is property of the University. If found misplaced, please return to the office of the issuing authority.
                        </td>
                    </tr>
                </tbody></table>
                
        
                
    
    
    
            </div>
        </div>

    </div>

</div>






<div class="first_page_container">

    <div class="first_page">
        <div class="header_section">
        <div class="row">
            <div class="col-md-2">
                <img src="http://64.227.161.177/assets/frontend/images/icon.png" style="height:45px;width:45px; margin-top: 10px; margin-left: 1px;">
            </div>
            <div class="col-md-10 text-white text-center">
                <h6>Dr. Shakuntala Misra<br>National Rehabilitation University</h6>
                <!--p>Govt. of Uttar Pradesh</p-->
                <p>Mohaan Road, Lucknow U.P. 226017</p>
                <p>Toll Free No. : 1800 180 0987</p>
                <p style="padding-bottom:8px">Website : https://dsmru.up.nic.in/</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-xs-9 col-9">
            <br><table style="width: 55mm;strong;font-size: 8px;margin-top:-18px;">
                <tbody><tr>
                    <td style="vertical-align: top; padding-left: 10px;"><strong style="font-weight: 600;">ID Card</strong> : SU2000000047</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Validity</strong> : Academic</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Name</strong> : ARCHANA PAL </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Date of Birth</strong> : 12/02/2003</td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Father's Name</strong> : JAI PRAKASH PAL</td>
                    <td style="padding-left: 10px;"></td>
				</tr>
				<tr>

                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Course</strong> : B.TECH (CE)</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                    <!--tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Subjects</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr--> 
                
                
                    <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Disability If any</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Gender</strong> : female</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Student's Contact No</strong> : 6386506741</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Nationality</strong> : Indian </td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Blood Group</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Mailing Address</strong> : <span style="font-size:8px;"></span></td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Local Guardian's Name</strong> :   </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Guardian's Contact No</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            </tbody></table>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3 col-3 mt-2">
			<div class="photo_here">
                				<p style="text-align: center">
                                        <img src="https://thebasmatihouse.com/storage/123737/IMG_20221215_101918_1.jpg">
                                    </p>
			</div>
		</div>
    </div>
    <div class="row">
        <div class="col-md-8 mt-2">
            <table style="width: 400px;font-size:15px;margin-top: -10px;">
               
            </table> 
        </div>
    </div>
  
</div>  


<div class="first_page">
        <div class="row ">
            <div class="col-md-12">
				<h4 style="text-align: center;margin-top: 5px;font-size: 12px;">Instructions</h4>
                <ol style="padding-left: 14px;font-size:8px;padding-right: 5px;margin:0px;">
                    <li>This identity card is non-transferable</li>
                    <li>Students must carry this card in this University Campus and are required to show on demand.</li>
                    <li>Misuse of the card is thus liable for punishment as per the University rules.</li>
                    <li>In case of loss / damage of this card Rs. 200/- will be charged for Issuing duplicate card.</li>
                </ol>

                <table class="qr_table">
                    <tbody><tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;Issued on : 27/11/2021</b>
                    </td></tr>
                    <tr>
					                        <td style="text-align:center;">
                            <img src="https://chart.googleapis.com/chart?chs=300x300&amp;cht=qr&amp;chl=ID Card : SU2000000047 Name : ARCHANA PAL Contact : 6386506741&amp;choe=UTF-8" style="width:70px;height:70px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 50px;">
							<div class="sign"><img src="http://64.227.161.177/signatures/vk_singh.png" style="width:100%;height:auto;"></div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 0px;">
							<div class="sign" style="margin-top: -5px;"><span class="sign_2">( Prof. V. K. Singh )</span>Proctor </div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:left;padding-top:5px;">
                            <strong>Note :</strong> This card is property of the University. If found misplaced, please return to the office of the issuing authority.
                        </td>
                    </tr>
                </tbody></table>
                
        
                
    
    
    
            </div>
        </div>

    </div>

</div>






<div class="first_page_container">

    <div class="first_page">
        <div class="header_section">
        <div class="row">
            <div class="col-md-2">
                <img src="http://64.227.161.177/assets/frontend/images/icon.png" style="height:45px;width:45px; margin-top: 10px; margin-left: 1px;">
            </div>
            <div class="col-md-10 text-white text-center">
                <h6>Dr. Shakuntala Misra<br>National Rehabilitation University</h6>
                <!--p>Govt. of Uttar Pradesh</p-->
                <p>Mohaan Road, Lucknow U.P. 226017</p>
                <p>Toll Free No. : 1800 180 0987</p>
                <p style="padding-bottom:8px">Website : https://dsmru.up.nic.in/</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-xs-9 col-9">
            <br><table style="width: 55mm;strong;font-size: 8px;margin-top:-18px;">
                <tbody><tr>
                    <td style="vertical-align: top; padding-left: 10px;"><strong style="font-weight: 600;">ID Card</strong> : SU1800001151</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Validity</strong> : Academic</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Name</strong> : ASHISH RAWAT </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Date of Birth</strong> : 22/02/1999</td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Father's Name</strong> : NAYLAL RAWAT</td>
                    <td style="padding-left: 10px;"></td>
				</tr>
				<tr>

                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Course</strong> : B.TECH (CE)</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                    <!--tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Subjects</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr--> 
                
                
                    <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Disability If any</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Gender</strong> : male</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Student's Contact No</strong> : 9628711832</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Nationality</strong> : Indian </td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Blood Group</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Mailing Address</strong> : <span style="font-size:8px;"></span></td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Local Guardian's Name</strong> :   </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Guardian's Contact No</strong> : </td>
                <td style="padding-left: 10px;"></td>
            </tr>
            </tbody></table>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3 col-3 mt-2">
			<div class="photo_here">
                				<p style="text-align: center">
                                        <img src="https://thebasmatihouse.com/storage/11512/IMG-20220222-WA0003.jpg">
                                    </p>
			</div>
		</div>
    </div>
    <div class="row">
        <div class="col-md-8 mt-2">
            <table style="width: 400px;font-size:15px;margin-top: -10px;">
               
            </table> 
        </div>
    </div>
  
</div>  


<div class="first_page">
        <div class="row ">
            <div class="col-md-12">
				<h4 style="text-align: center;margin-top: 5px;font-size: 12px;">Instructions</h4>
                <ol style="padding-left: 14px;font-size:8px;padding-right: 5px;margin:0px;">
                    <li>This identity card is non-transferable</li>
                    <li>Students must carry this card in this University Campus and are required to show on demand.</li>
                    <li>Misuse of the card is thus liable for punishment as per the University rules.</li>
                    <li>In case of loss / damage of this card Rs. 200/- will be charged for Issuing duplicate card.</li>
                </ol>

                <table class="qr_table">
                    <tbody><tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;Issued on : 27/11/2021</b>
                    </td></tr>
                    <tr>
					                        <td style="text-align:center;">
                            <img src="https://chart.googleapis.com/chart?chs=300x300&amp;cht=qr&amp;chl=ID Card : SU1800001151 Name : ASHISH RAWAT Contact : 9628711832&amp;choe=UTF-8" style="width:70px;height:70px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 50px;">
							<div class="sign"><img src="http://64.227.161.177/signatures/vk_singh.png" style="width:100%;height:auto;"></div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 0px;">
							<div class="sign" style="margin-top: -5px;"><span class="sign_2">( Prof. V. K. Singh )</span>Proctor </div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:left;padding-top:5px;">
                            <strong>Note :</strong> This card is property of the University. If found misplaced, please return to the office of the issuing authority.
                        </td>
                    </tr>
                </tbody></table>
                
        
                
    
    
    
            </div>
        </div>

    </div>

</div>


</div>



<div class="page_break">
<div style="height:5px;width:100%;clear:both;" class="clearfix">&nbsp;</div>









 








{{-- @include("footer") --}}








<div class="row" style="width:100%;"></div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    

</div></div>

{{-- </body></html> --}}
@endsection