
class PanelOportunidades{

  constructor(data, htmlElement, moveKanbanColumnURL, moveKanbanItemURL, verEditarOportunidadURL, verPresupuestoURL, nuevoPresupuestoURL){
    this.data = data;
    this.htmlElement = htmlElement;
    this.moveKanbanColumnURL = moveKanbanColumnURL;
    this.moveKanbanItemURL = moveKanbanItemURL;
    this.verEditarOportunidadURL = verEditarOportunidadURL;
    this.verPresupuestoURL = verPresupuestoURL;
    this.nuevoPresupuestoURL = nuevoPresupuestoURL;
    this.init();
  }

  init()
  {
    this.boards = this.parseDatajKanban(this.data);
    this.panelKanban = new jKanban({
        element : this.htmlElement,
        //gutter  : '10px',
        responsivePercentage:true,
        //addItemButton: true,
        itemHandleOptions: {
          enabled:true
        },
        dragendBoard: (el) => {this.moveKanbanColumn(el);},
        dragendEl: (el) => {this.moveKanbanItem(el);},
        click: function(el){
          var item = $("div[data-eid=" + el.dataset.eid + "]").get(0);
          $("#oportunidad-descripcion-" + $(item).data('eid')).toggleClass("d-none");
        },
        boards:this.boards
      });

    this.addDescription();
    this.boards_order = this.obtainColumnOrder();
    this.boards_data = this.obtainColumnData();

  }
  addDescription()
  {

    for(var i=0; i<this.boards.length; i++){
      for(var j=0; j<this.boards[i].item.length; j++){
        var item = $("div[data-eid=" +this.boards[i].item[j].id + "]").get(0);
        var id = (this.boards[i].item[j].id).replace("item-","");
        var ele = this.data.find(x => x.oportunidades.find(y=> y.id==id) != undefined).oportunidades.find(y => y.id==id);

        var htmlAppend = '';
        htmlAppend += '<div class="d-none" id="oportunidad-descripcion-' + $(item).data('eid') + '">';
        htmlAppend += '<hr/><b>Descripción: </b><i class="text-gray-600 d-block">' + $(item).data('descripcion') + '</i>';
        htmlAppend += '<br><b>Cliente</b> <span class="float-right text-gray-600">' + $(item).data('nombre_comercial_cliente') + '</span>';
        htmlAppend += '<br><b>Responsable</b> <span class="float-right text-gray-600">' + $(item).data('responsable') + '</span>';
        htmlAppend += '<br/><b>Creada:</b> <span class="float-right text-gray-600">' + $(item).data('fechacreacion') + '</span>';
        htmlAppend += '<br/><b>Ingreso Estiamdo:</b> <span class="float-right text-gray-600">' + $(item).data('ingresoestimado') + ' €</span>';
        htmlAppend += '<br/><b>Porc. Exito Estimado</b> <span class="float-right text-gray-600">' + $(item).data('porcentajeexitoestimado')*100 + ' %</span>';
        htmlAppend += '<hr/><div><a href="' + this.verEditarOportunidadURL + '/' + id + '" class="btn btn-info btn-circle mr-2"><i class="fa fa-fw fa-edit"></i></a>';
        htmlAppend += '<a href="' + this.verEditarOportunidadURL + '/' + id + '/ganada" class="btn btn-success btn-circle mr-2"><i class="fa fa-fw fa-check"></i></a>';
        htmlAppend += '<a href="javascript:void(0)" onclick=\'$("#motivo-perdida-' + id + '").slideToggle();\' class="btn btn-warning btn-circle mr-2"><i class="fa fa-fw fa-times"></i></a>';
        htmlAppend += '<a href="' + this.verEditarOportunidadURL + '/' + id + '/eliminar" onclick="return confirm(\'¿Seguro que deseas eliminar este elemento?\');" class="btn btn-danger btn-circle mr-2"><i class="fa fa-fw fa-trash"></i></a>';
        htmlAppend += '<a href="' + (ele.presupuesto==null?this.nuevoPresupuestoURL + '?id_oportunidad=' + ele.id:this.verPresupuestoURL + '/' + ele.presupuesto.id) + '" class="btn btn-primary btn-circle mr-2"><i class="fa fa-fw fa-' + (ele.presupuesto==null?'plus':'file-invoice') + '"></i></a></div>';
        htmlAppend += '<div id="motivo-perdida-' + id + '" class="pt-2 mt-3 alert alert-warning" style="display:none"><form action="' + this.verEditarOportunidadURL + '/' + id + '/perdida" method="POST"><label>Motivo:</label><input type="text" required class="form-control" name="motivo-perdida"></input><input type="submit" class="btn btn-warning mt-1 btn-block" value="Perdido"></form></div>';
        htmlAppend += '</div>';
        $(item).append(htmlAppend);

      }


    }
  }
  parseDatajKanban(data)
  {

    var boards = [];
    for(var i=0;i<data.length;i++){
      var aux = {'id': 'column-' + data[i].id,'title': data[i].nombre,'class':'bg-info',item:[]};
      for(var j=0;j<data[i].oportunidades.length;j++){
        var opo = data[i].oportunidades[j];
        var oportunidadaux = {'id':'item-' + opo.id,'title':opo.nombre, 'descripcion':opo.descripcion,'nombre_comercial_cliente':opo.cliente.nombreComercial ,  'responsable':opo.responsable.email, 'fechacreacion':new Date(opo.fechaCreacion).toLocaleString(), 'ganada':opo.ganada, 'ingresoestimado':opo.ingresoEstimado, 'porcentajeexitoestimado':opo.porcentajeExitoEstimado, 'shown':false};
        aux.item.push(oportunidadaux);
      }
      boards.push(aux);
    }
    return boards;
  }

  obtainColumnOrder()
  {
    var column_order = {};
    for(var i = 0; i < this.boards.length; i++)
    {
      column_order[this.boards[i].id]=$(this.panelKanban.findBoard(this.boards[i].id)).attr('data-order');
    }
    return column_order;
  }

  obtainColumnData()
  {
    var column_data = {};
    for(var i=0; i<this.boards.length; i++)
    {
      var auxitems = $(this.panelKanban.getBoardElements(this.boards[i].id));
      var items = [];
      for(var j=0;j<auxitems.length;j++)
      {
        items.push($(auxitems[j]).attr('data-eid'));
      }
      column_data[this.boards[i].id]=items;
    }
    return column_data;
  }

  moveKanbanColumn(el)
  {
    var new_order = $(el).attr("data-order");
    if(new_order!=this.boards_order[$(el).attr("data-id")]){
      this.boards_order = this.obtainColumnOrder();
      //Llamada post
      $.post(this.moveKanbanColumnURL,this.boards_order);
    }
  }

  moveKanbanItem(el)
  {

    var new_column = this.panelKanban.getParentBoardID($(el).attr('data-eid'));
    if(!this.boards_data[new_column].includes($(el).attr('data-eid'))){
        //this.boards_data = this.obtainColumnData();
        //this.updateTotales(el);
        //Llamada post

        $.post(this.moveKanbanItemURL,this.obtainColumnData(),
          (data) => {this.boards_data = this.obtainColumnData();this.data = data;this.updateTotales()}
        );

    }
  }

  updateTotales()
  {

    for(var i=0;i < this.data.length; i++){
      var total = 0;
      for(var j=0; j < this.data[i].oportunidades.length; j++){
        total+= (this.data[i].oportunidades[j].ingresoEstimado * this.data[i].oportunidades[j].porcentajeExitoEstimado);
      }
      var ele = panel_oportunidades.panelKanban.findBoard("column-" + this.data[i].id);
      total = (Math.round(total*100)/100)
      $(ele).find('.badge-success').html(total + ' €');
    }
  }

}
