$(document).ready(function () {

    //Vehicle Color
    vehicleType = $('#gregumo_starsrentalbundle_vehicle_type');
    vehicleType.on('change', function () {
        vehicleColorToggleDisplay( $( 'option:selected', this ).text() );
    });
    vehicleColorToggleDisplay( $( 'option:selected', vehicleType ).text() );

    //Booking Upgrade
    isUpgradableBtn = $('button.isUpgradable');
    bookingUpgrade = $('#gregumo_starsrentalbundle_booking_upgrade');
    bookingVehicle = $('#gregumo_starsrentalbundle_booking_vehicle');
    bookingCustomer = $('#gregumo_starsrentalbundle_booking_customer');

    isUpgradableBtn.on('click', function () {
        url = 'isUpgradable/' + bookingVehicle.val() + '/' + bookingCustomer.val();
        $.get( url, function( data ) {

            if(!data.superiorTypeExists){
                alert("Il n’existe pas de meilleure gamme que les " + data.currentTypeName);
            }else if(!data.upgradable){
                alert(data.customerName + " ne peut être surclassé sur les " + data.superiorTypeName + " et doit donc rester sur les " + data.currentTypeName);
            }else{
                alert(data.customerName + " peut être surclassé sur les " + data.superiorTypeName );
                bookingUpgrade.parent('div').show();
            }

        });
    });
    bookingUpgrade.parent('div').hide();

});

function vehicleColorToggleDisplay( vehicleType )
{
    vehicleColor = $('#gregumo_starsrentalbundle_vehicle_color');
    vehicleColorParent = vehicleColor.parent('div');

    if( 'TieFighter' != vehicleType ) {
        vehicleColor.val('');
        vehicleColorParent.hide();
    }else {
        vehicleColorParent.show();
    }

}