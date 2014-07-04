<?     
require("header.php"); 
require("classes/bitstampPreciosClass.php");

$oPreciosBitstamp = new BitstampPrecios();
$btcPrecio=$oPreciosBitstamp->getBtcPrecio();
$eurPrecio=$oPreciosBitstamp->getEurPrecio();
$btcBajo=$oPreciosBitstamp->getLowPrecio();
$btcAlto=$oPreciosBitstamp->getHighPrecio();
$btcCompra=$oPreciosBitstamp->getBidPrecio();
$btcVenta=$oPreciosBitstamp->getAskPrecio();
$btcVolumen=$oPreciosBitstamp->getVolume();
?>
<!-- Cuerpo propio pagina -->
      <div class="row">
           <div class="col-md-10 col-md-offset-1">
               <table class="table">
                   <tr>
                       <td>
                           Precio del Bitcoin
                       </td>
                       <td>
                            <? echo $btcPrecio ?>
                       </td>
                   </tr>
                   <tr>
                       <td>
                           Precio del Euro - Dolar 
                       </td>
                       <td>
                           <? echo $eurPrecio ?>
                       </td>
                   </tr>
                   <tr>
                       <td>
                           Precio del Bitcoin más bajo en 24h 
                       </td>
                       <td>
                          <? echo $btcBajo ?>
                       </td>
                   </tr>
                   <tr>
                       <td>
                           Precio del Bitcoin más alto en 24h 
                       </td>
                       <td>
                           <? echo $btcAlto ?>
                       </td>
                   </tr>
                   <tr>
                       <td>
                           Precio del Bitcoin más alto de compra 
                       </td>
                       <td>
                          <? echo $btcCompra ?> 
                       </td>
                   </tr>
                   <tr>
                       <td>
                           Precio del Bitcoin más bajo de venta
                       </td>
                       <td>
                           <? echo $btcVenta ?>
                       </td>
                   </tr>
                   <tr>
                       <td>
                           Volumen total de Bitcoins en 24h 
                       </td>
                       <td>
                           <? echo round($btcVolumen) ?> B -> <? echo round(($btcVolumen*$btcPrecio)/$eurPrecio) ?> €
                       </td>
                   </tr>
               </table>
           </div>
      </div>

<!-- /Cuerpo propio pagina -->
<?
       require("footer.php");	
?>
