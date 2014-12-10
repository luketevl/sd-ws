create database banco_ws;
use banco_ws;

create table contas(
	id_cont int UNSIGNED AUTO_INCREMENT  PRIMARY KEY NOT NULL,
	num_conta int not null,
	saldo double(9,2) not null
	)engine=MYiSAM;
