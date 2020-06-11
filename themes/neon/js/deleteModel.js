/**
 * Created by VivaInovacao on 30/09/2015.
 */
var grid = $('.dataTables_wrapper  ').attr('id');
$('.dataTables_wrapper a.deletar').live('click', function () {
        var url = $(this).attr('href');
        swal({   
           title: "Deseja confirmar a exclusão?",
           text: "", 
           type: "warning", 
           showCancelButton: true, 
           showLoaderOnConfirm: true,
            confirmButtonColor: "#3cb371",
           confirmButtonText: "Confirmar",
           cancelButtonText: "Cancelar",
           closeOnConfirm: false
       }, function(){
        $.ajax({
            type: 'POST',
            cache: false,
            url: url,
            success: function (data) {
                $.fn.yiiGridView.update(grid);
                swal("", "Exclusão realizada com sucesso.", "success");
            }
        });        
      });
});
