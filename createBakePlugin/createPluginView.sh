#!/bin/bash

##
# Viewファイルを作成
##
echo "cd ${SET_DIR}/app/Console/"
cd ${SET_DIR}/app/Console/

CAKE_BAKE="bake view ${PLUGIN_NAME} -c master --plugin ${PLUGIN_NAME}"
echo "./cake ${CAKE_BAKE}"
./cake ${CAKE_BAKE}

