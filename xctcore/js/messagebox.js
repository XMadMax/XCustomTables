jQuery(document).ready(function($){
    $(document).on('mouseenter','.xctimage',function(){
        $(this).css( 'cursor', 'pointer' );
    });
    $(document).on('mouseleave','.xctimage',function(){
        $(this).css( 'cursor', 'auto' );
    });
    $(document).on('click','.xctimage',function(){
           var srcfile = $(this).attr('src');
           var posx = $(this).offset().left;
           var posy = $(this).offset().top;
           $("#xctmodal").css("width","");
           $("#xctmodalimage").attr('src',srcfile);
           $("#xctmodal").css({top:posy-40,left:posx-200});
           $(".xctmodalimagediv").show();
           $("#xctmodal").slideDown();
           $("#xctmodalimage").attr('title',srcfile+' \nWidth:'+$("#xctmodalimage").width()+" \nHeight:"+$("#xctmodalimage").height());
           $("#xctmodal").on('click',function(){
                $("#xctmodal").slideUp(function(){
                    $(".xctmodalimagediv").hide();
                    $(".xctmodaltitlediv").hide();
                    $(".xctmodalmessagediv").hide();
                });
           });
    });
    
    var xctShowMessage = (function(title,message,self) {
        var posx = self.offset().left;
        var posy = self.offset().top;
        $("#xctmodal").css({top:posy-40,left:posx-200,width:'350px'});
        $(".xctmodalimagediv").hide();
        $(".xctmodaltitlediv").html('<h2>'+title+'</h2>');
        $(".xctmodalmessagediv").html('<p>'+message+'</p>');
        $(".xctmodaltitlediv").show();
        $(".xctmodalmessagediv").show();
        $("#xctmodal").slideDown();
        $("#xctmodal").on('click',function(){
             $("#xctmodal").slideUp(function(){
                $(".xctmodalimagediv").hide();
                $(".xctmodaltitlediv").hide();
                $(".xctmodalmessagediv").hide();
             });
        });
        
    });
    
    $.fn.xctShowMessage = function (title,message) {
        xctShowMessage(title,message,this);
        return this;
    }
    
});