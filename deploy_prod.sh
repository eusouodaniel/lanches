#!/bin/bash

dir_release1="verde_"
dir_release2=`date +%Y_%d_%m_%H_%M`

dir_release="$dir_release1$dir_release2"

if ssh oa6o4uatfmcg@23.229.234.231 "[ ! -d release/ ]"
then
	echo "Agora digite a senha para realizar a operação de deploy"
	echo "Obs: realiza um backup do primeiro deploy no diretorio release/"
	ssh oa6o4uatfmcg@23.229.234.231 \
	"mkdir release &&" \
	'zip -r projeto.zip ~/ -x*.composer/* -x*.git/* -x*.cpanel/* -x*.etc/* -x*.public_html/* -x*app/cache/* -x*vendor/* &&' \
	"mv projeto.zip release/ && cd release/ && mv projeto.zip $dir_release.zip &&" \
	'rm -f -r ~/projeto.zip';
else
	echo "Agora digite a senha para realizar um backup da ultima versão do deploy"
	ssh oa6o4uatfmcg@23.229.234.231 \
	'rm -f -r release/* &&' \
	'zip -r projeto.zip ~/ -x*.composer/* -x*.git/* -x*.cpanel/* -x*.etc/* -x*.public_html/* -x*app/cache/* -x*vendor/* &&' \
	"mv projeto.zip release/ && cd release/ && mv projeto.zip $dir_release.zip &&" \
	'rm -f -r ~/projeto.zip';
fi

rsync -avz -e "ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null" --progress --exclude-from 'deploy/ignore.txt' . bella@bellamocaestetica.com.br:~/
