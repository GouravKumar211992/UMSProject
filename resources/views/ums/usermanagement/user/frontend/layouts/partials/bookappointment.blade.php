<div class="modal fade rightModal" id="bookappoint" tabindex="-1" role="dialog" aria-labelledby="bookappoint" aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout" role="document">
            <div class="modal-content">
                <form class="needs-validation" novalidate>
                    <div class="modal-header pt-5 pl-5 pr-5 border-0">
                        <div class="popTitle">
                            <span class="appointment-popup-title">Online</span> appointment
                        </div>
                        <div class="float-right">
                            <button type="button" class="closebtn" data-dismiss="modal" aria-label="Close">Close <img src="/assets/frontend/images/close-line.svg" /></button>
                        </div>
                    </div>
                    <div class="modal-body pt-3 pr-5 pl-5">
                        <div class="cancelAppmnt">
                            <div class="row">
                                <!-- <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="currency">Select month</label>
                                        <select id="currency" class="form-control">
                                      <option selected="">January 2021</option>
                                      <option>Febraury 2021</option>
                                    </select>
                                    </div>
                                </div> -->
                                <input type="hidden" id="designerid" name="designer_id" value="{{@$designer->id}}">
                                <div class="col-sm-12">
                                    <div class="calendar">
                                        <h5 class="mb-4">Availability</h5>
                                        <input id="appointmentdate"  type="hidden" name="appointmentdate">
                                        <input id="oldappointmentid"  type="hidden" name="oldappointmentid">
                                        <!-- div id="appointmentcalendar"></div -->
                                        <!-- Calendly inline widget begin -->
                                        <div id="calendlyWidget" class="calendly-inline-widget" data-auto-load="false" style="width:400px; height:630px"></div>
                                    </div>
                                </div>

                                <!-- div class="col-sm-12">
                                    <div class="selTimeslot" id="selTimeslot">
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="calstatus"><i class="fa fa-square available"></i> Available</div>
                                    <div class="calstatus"><i class="fa fa-square unavailable"></i> Unavailable</div>
                                </div -->
                                @isset($designer)
                                <div class="col-sm-12 mt-4">
                                    <p class="f-16 mb-0">Consultation Fee: 

                                        {{currencyConvertHTML($designer->currency,(int)$designer->consultation_fee)}}

                                    </p>
                                    <small class="f-11" style="opacity:0.7">This fee will be deducted from the final amount in case a purchase is made.</small>
                                </div>
                                @endisset
                                <div class="col-sm-12">
                                    <div id="error"></div>
                                    <div id="appointmentsucess"></div>
                                </div>
                                <div class="col-sm-12 ">
                                    <button id="bookapointment" style="display: none" class="btn btn-outline-dark btn-rounded btn-block my-4 z-depth-0 waves-effect waves-light" type="button">CONFIRM APPOINTMENT</button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>
<script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js"></script>
<script>
    function showScheduleWidget(designer_id){
        Calendly.initInlineWidget({
            url: `https://calendly.com/${designer_id}/30min?hide_landing_page_details=1&hide_gdpr_banner=1`,
            parentElement: document.getElementById('calendlyWidget'),
            prefill: {
                name: "{{@$user->name}}",
                email: "{{@$user->email}}",
            }
        });
    }

    function showRescheduleWidget(event_id){
        Calendly.initInlineWidget({
            url: `https://calendly.com/reschedulings/${event_id}?hide_landing_page_details=1&hide_gdpr_banner=1`,
            parentElement: document.getElementById('calendlyWidget'),
            prefill: {
                name: "{{@$user->name}}",
                email: "{{@$user->email}}",
            }
        });
    }

    function isCalendlyEvent(e) {
        return e.data.event &&
            e.data.event.indexOf('calendly') === 0;
    }

    window.addEventListener(
        'message',
        function(e) {
            if (isCalendlyEvent(e)) {
                console.log(e.data);
                if(e.data.event==="calendly.event_scheduled"){
                    d = e.data.payload.event.uri.split('/');
                    bookAppointment(d[d.length-1]);
                }
            }
        }
    );

    function bookAppointment(event){
        $('#error').html('');
        var designer_id = $('#designerid').val();
        var data = {
            designer_id:designer_id,
            calendly_event: event,
            '_token' : '{{{csrf_token()}}}'
        }
        $.ajax({
            url: '/user/bookappointment_calendly',
            type: 'POST',
            data: data,
            success:function(res) {

                var message = res.message;
                if(res.payment_url) {
                    location.href =res.payment_url;
                }
                $("#appointmentsucess").html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                
            },
            error :function( data ) {
                if( data.status === 422 ) {
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function (key, value) {
                        if($.isPlainObject(value)) {
                            $.each(value, function (key, value) {
                                //console.log(key+ " " +value);
                                key  =  key.charAt(0).toUpperCase() + key.slice(1);
                                $('#error').html(value);


                            });
                        }else{

                        }
                    });
                }

            }
        })
    }
</script>