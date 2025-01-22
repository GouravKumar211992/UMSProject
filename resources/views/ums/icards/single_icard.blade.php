{{-- <!DOCTYPE html>
<html lang="en"><head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student::Icard</title> --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="/assets/admin/css/style_icard.css" rel="stylesheet">
    <style>
    .header_section{
        background-color: #ff7703;
        border-radius: 5px 5px 0px 0px;
        background-image : url("http://64.227.161.177/images/logo.png");
        background-position: 10px;
        background-repeat: no-repeat;
        background-size: 80px;
        padding-top: 18px;
        padding-left: 0px;
    }
	.header_section p {
		margin:0px;
		font-size:14px;
	}
    .photo_here{
        border: black thin solid;
        padding: 2px;
        width: 100px;
        height: 115px;
        margin-left: -38px;
        border-radius: 5px;
    }
    .photo_here img{
		height: 110px !important;
		width: 90% !important;
	}
    .first_page{
         width: 370px;
         height: 590px;
         margin-top: 10px;
         margin-left: 50px;
         border: 1px #000 solid;
         border-radius: 5px;
    }
    .sign{
        text-align: center;
        width: 170px;
        float: right;
        font-size: 15px;
        font-weight: 600;
        
    }
    .sign_2{
        font-size: 15px;
        display:block;
    }
	@media  print {
		body {-webkit-print-color-adjust: exact;}
		body
		{
		  margin:
		  /*0 100px 0 0;*/
		}
		.print_hide{
			display:none;
		}
		.page_break {page-break-after: always;}
		.first_page{
			margin: 0px;
		}
	}
    </style>


{{-- </head>
<body>
    @include("header")
    @include("sidebar") --}}



<div class="containers">
<div class="row print_hide">
	<div class="col-md-12 text-center">
		<button onclick="window.print()" class="btn btn-primary" style="margin-top: 10px;width: 12%;font-size: 17px;font-weight: 700;">Print</button>
	</div>
</div>

<div class="ecard-container row">
<div class="col-md-6">

    <div class="first_page" style="float:right;">
        <div class="header_section">
        <div class="row">
            <div class="col-md-3">
                <img src="http://64.227.161.177/assets/frontend/images/icon.png" style="height:102px;width:102px; margin-top: 18px; margin-left: 1px;">
            </div>
            <div class="col-md-9 text-white text-center">
                <h5>Dr. Shakuntala Misra</h5>
				<p style="font-size:16px;">National Rehabilitation University</p>
                <!--p>Govt. of Uttar Pradesh</p-->
                <p>Mohaan Road, Lucknow U.P. 226017</p>
                <p>Phone No:-  9568745632</p>
                <p style="padding-bottom:15px">Website https://dsmru.up.nic.in/</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-xs-9 col-9">
            <br><table style="width: 400px;strong;font-size: 15px;">
                <tbody><tr>
                    <td style="vertical-align: top; padding-left: 10px;"><strong style="font-weight: 600;">ID Card</strong> : SU1700000843</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Validity</strong> : 2017-2018</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Name</strong> : SURENDRA KUMAR </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Date of Birth</strong> : 15/10/1995</td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Father's Name</strong> : Munendra Singh</td>
                    <td style="padding-left: 10px;"></td>
				</tr>
				<tr>

                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Course</strong> : M.Sc</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                    <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Subjects</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                
                
                    <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Disability If any</strong> : </td>
                    <td style="padding-left: 10px;"></td>
                </tr> 
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Gender</strong> : male</td>
                    <td style="padding-left: 10px;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><strong style="font-weight: 600;">Student's Contact No</strong> : 9695799682</td>
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
                <td style="padding-left: 10px;"><strong style="font-weight: 600;">Corresponding Address</strong> : </td>
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
        <div class="col-lg-3 col-md-3 col-xs-3 col-3 mt-2"><div class="photo_here">
			            <p style="text-align: center"><img src="http://64.227.161.177/MAY21_PHOTO/176180012.jpg"></p>
			        </div></div>
    </div>
    <div class="row">
        <div class="col-md-8 mt-2">
            <table style="width: 400px;font-size:15px;margin-top: -10px;">
               
            </table> 
        </div>
    </div>
  
</div>  

</div>  
<div class="col-md-6">

<div class="first_page">
        <div class="row ">
            <div class="col-md-12">
                <h3 style="text-align: center;margin-top:15px;">Instructions</h3>
                <ol style="padding-left: 30px;font-size:15px; ">
                    <li>This identity card is non-transportable</li>
                    <li>Students must carry this card in this University Campus and are required to show on demand.</li>
                    <li>Misuse of the card is thus liable for punishment as per the University rules.</li>
                    <li>In case of loss / damage of this card Rs. 200/- will be charged for Issuing duplicate card.</li>
                
                <br>
                <table style="width:100%;">
                    <tbody><tr>
                        <td><b>Issued on : 13/01/2025</b>
                    </td></tr>
                    <tr>
					                        <td style="text-align:center;">
                            <img src="https://chart.googleapis.com/chart?chs=300x300&amp;cht=qr&amp;chl=ID Card : SU1700000843 Name : SURENDRA KUMAR Contact : 9695799682&amp;choe=UTF-8">
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 50px;">
							<div class="sign"><img src="http://64.227.161.177/signatures/vk_singh.png" style="width:70%;height:auto;"></div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:right;padding-left: 0px;">
							<div class="sign"><span class="sign_2">( Prof. V. K. Singh )</span>Proctor </div>
						</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: bottom; text-align:left;padding-top:5px;">
                            <strong>Note :</strong> This card is property of the University. If found misplaced, please return to the office of the issuing authority.
                        </td>
                    </tr>
                </tbody></table>
                
        
                
    
    
    
            </ol></div>
        </div>

    </div>

</div>  
</div>
</div>
{{-- @include("footer") --}}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    

</body></html>