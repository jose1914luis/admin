#!/bin/bash
echo " "
echo "----------------------------------------------------"
echo "ID de la Session: $1"
echo "Generando shape de Solicitudes ...."
pgsql2shp -f "DwnShapes/SolSIGMIN_Bog_$1" -u cmqpru -P 2012zygMin cmqpru "select * from dwn_solicitudes_bog where id_session='$1'"

echo "Generando shape de titulos ...."
pgsql2shp -f "DwnShapes/TitSIGMIN_Bog_$1" -u cmqpru -P 2012zygMin cmqpru "select * from dwn_titulos_bog where id_session='$1'"

echo "Comprimiendo archivos para descarga ...."
zip DwnShapes/geoSIGMIN_Bog_$1 DwnShapes/SolSIGMIN_Bog_$1* DwnShapes/TitSIGMIN_Bog_$1*
rm DwnShapes/SolSIGMIN_Bog_$1* DwnShapes/TitSIGMIN_Bog_$1*

echo "----------------------------------------------------"
echo "Fin de la operacion"