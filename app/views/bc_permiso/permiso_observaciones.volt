<ul style="margin-bottom: 5px;" class="nav nav-tabs" role="tablist"><li role="presentation" class="active"><a>Histórico de Eventos</a></li></ul>
<? foreach($permiso->BcPermisoObservacion as $row){ ?>
	<div class="observacion">
   		<div class="header">
		  <div class="foto">
		    <img src="{{ row.IbcUsuario.foto }}" width="40px" height="40px">
		  </div>
		  <div>
		  	<h3 style="margin: 0px;">{{ row.IbcUsuario.nombre }} <span class="label label-{{ permiso.getEstadoStyle() }}">{{ permiso.getEstado() }}</span></h3>
   			<div class="info_anuncio"><?php $date = date_create($row->fechahora); ?><i class="fa fa-calendar"></i> <?php echo date_format($date, 'd/m/Y'); ?> <i class="fa fa-clock-o"></i> <?php echo date_format($date, 'G:ia'); ?> <span class="label label-success"></span></div>
		  </div>
		</div>
   		<div class="contenido">{{ row.observacion }}</div>
   	</div>
<?php } ?>