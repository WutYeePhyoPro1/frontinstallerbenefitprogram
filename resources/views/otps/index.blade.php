@extends("layouts.front.customerindex")


@section("content")

        <div class="row">
                <div class="card px-0 signincards">


                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center">
                            <div class="col-md-6">
                                <div class="otpicons">
                                    <img src="{{ asset('images/otpsend.png') }}" alt="" width="80" height="80">
                                </div>
                                <label for="" class="text-center title">Verification</label>
                                <p class="mt-1">We will send to you a One Time Password on your phone number</p>

                                <div class="input-container row align-items-center">
                                    <h5><a href="javascript:void(0);" class="flex-fill text-nowrap text-primary font-weight-bold getotp-btns">GET OTP</a></h5>
                                    <input type="number" id="otp_number" name="otp_number" class="otp_number"  min="100000" max="999999"  maxlength="6" placeholder="Enter 6-digit OTP" oninput="enforceSixDigits(this)" />
                                </div>
                                <p class="mt-1">Didn't receive verification OTP? <a href="#" class="flex-fill text-nowrap text-primary font-weight-bold">Resend Again</a></p>
                                <button type="button" id="verify-btn" class="btn btn-primary btn-sm">Verify</button>
                            </div>
                        </div>


                    </div>
                </div>
        </div>
@endsection




@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".getotp-btns").click(function(){
            Swal.fire({
                title: "Processing....",
                // html: "I will close in <b></b> milliseconds.",
                text: "Please wait while we send your OTP",
                allowOutsideClick:false,
                didOpen: () => {
                     Swal.showLoading();
                }
           });


            const otp_number = $("#otp_number").val();
            {{-- console.log(otp_number); --}}

            $.ajax({
                url:"/generateotps/signin",
                type:"GET",
                success:function(response){
                     console.log(response);
                     Swal.close();

                     {{-- $("#otpmessage").text("Your OTP code is "+response.otp);
                     $("#otpmodal").modal("show");

                     startotptimer(60); // OTP will expires in 120s (2 minute); --}}
                },
                error:function(response){
                     console.error("Error: ",response);
                }
           })

        });
    });

    {{-- Start OTP Input --}}
    function enforceSixDigits(input) {
        // Limit the input value to 6 digits
        if (input.value.length > 6) {
            input.value = input.value.slice(0, 6);
        }
    }
    {{-- End OTP Input --}}

</script>
@endsection
