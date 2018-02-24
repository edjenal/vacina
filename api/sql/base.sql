drop table if exists vacina;
drop table if exists animal;
drop table if exists especie_tipo_vacina;
drop table if exists tipo_vacina;
drop table if exists especie;
drop table if exists usuario;

SET sql_mode = '';   

create table usuario (
  id int auto_increment not null,
  email varchar(50),
  date_created timestamp default now(),
  ativo boolean not null default true,
  senha varchar(50) not null,
  primary key (id),
  constraint uc_usuario unique (id, email)
) ENGINE=InnoDB CHARACTER SET=utf8 COLLATE utf8_unicode_ci;
insert into usuario (email, senha) values ('teste@teste.com', '81dc9bdb52d04dc20036dbd8313ed055');

create table especie(
	id int auto_increment not null,
	descricao varchar(50) not null,
	ativo boolean not null default true,
	primary key (id)
)ENGINE=InnoDB CHARACTER SET=utf8 COLLATE utf8_unicode_ci;
/*inserindo espécies;*/
insert into especie (descricao) values ('Canino (Cão)');
insert into especie (descricao) values ('Felino (Gato)');
insert into especie (descricao) values ('Equino (Cavalo)');
insert into especie (descricao) values ('Bovino (Boi)');
insert into especie (descricao) values ('Caprino (Bode/Cabra)');
insert into especie (descricao) values ('Ovino (Carneiro/Ovelha)');
insert into especie (descricao) values ('Suíno (Porco)');
insert into especie (descricao) values ('Galináceo/Aves (Galinhas em geral)');
insert into especie (descricao) values ('Bubalino (Búfalo)');
insert into especie (descricao) values ('Outros');
/*insert into especie (descricao) values ('');*/

create table tipo_vacina (
	id int auto_increment not null,
	id_especie int not null,
	nome varchar(50) not null,
	descricao varchar(200) not null,
	ativo boolean not null default true,
	primary key (id)	
)ENGINE=InnoDB CHARACTER SET=utf8 COLLATE utf8_unicode_ci;
alter table tipo_vacina add constraint fk_tipo_vacina_especie foreign key (id_especie) references especie(id);

/*Inserindo os tipos das vacinas;*/
/*cão-1*/
insert into tipo_vacina (id_especie, nome, descricao) 
	values (1, 'V8','Cinomose, Hepatite Infecciosa, Adenovírus Canino Tipo 2, Coronavírus Canino, Parainfluenza Canina, Parvovírus Canino e Leptospirose.');
insert into tipo_vacina (id_especie, nome, descricao) 
	values (1, 'V10','Cinomose, Hepatite Infecciosa, Adenovírus Canino Tipo 2, Coronavírus Canino, Parainfluenza Canina, Parvovírus Canino e Leptospirose.');
insert into tipo_vacina (id_especie, nome, descricao) 
	values (1, 'Gripe Canina','Adenovírus Canino Tipo 2, Parainfluenza Canina e Bordetella Bronchiseptica.');
insert into tipo_vacina (id_especie, nome, descricao) 
	values (1, 'Gardíase','Indicada para animais que vivem em grupos como canis, criadores ou locais com muitos cães que vivem em ambientes mais úmido.');
insert into tipo_vacina (id_especie, nome, descricao) 
	values (1, 'Antirrábica', 'Raiva');
insert into tipo_vacina (id_especie, nome, descricao) 
	values (1, 'Rinotraquete Infecciosa', 'Tosse dos canis');

/*gato-2*/
insert into tipo_vacina (id_especie, nome, descricao) 
	values (2, 'V3', 'Panleucopenia, Rinotraqueíte e Calicivirose');
insert into tipo_vacina (id_especie, nome, descricao) 
	values (2, 'V4', 'Panleucopenia, Rinotraqueíte, Calicivirose e Clamidiose');
insert into tipo_vacina (id_especie, nome, descricao) 
	values (2, 'V5', 'Panleucopenia, Rinotraqueíte, Calicivirose, Clamidiose e Leucemia Felina');
insert into tipo_vacina (id_especie, nome, descricao) 
	values (2, 'Antirrábica', 'Raiva');

create table animal(
	id int auto_increment not null,
	id_especie int not null,
	id_usuario int not null,
	descricao varchar(50) not null,
	ativo boolean not null default true,
	primary key (id)
)ENGINE=InnoDB CHARACTER SET=utf8 COLLATE utf8_unicode_ci;
alter table animal add constraint fk_animal_especie foreign key (id_especie) references especie(id);
alter table animal add constraint fk_animal_usuario foreign key (id_usuario) references usuario(id);

create table vacina(
	id int auto_increment not null,
	id_animal int not null,
	id_tipo_vacina int,
	tipo varchar(50),
	dt_tomou timestamp not null,
	dt_validade timestamp not null,
	tomou boolean default false,
	lote varchar(50) not null,
	primary key (id)
)ENGINE=InnoDB CHARACTER SET=utf8 COLLATE utf8_unicode_ci;
alter table vacina add constraint fk_vacina_animal foreign key (id_animal) references animal(id);
alter table vacina add constraint fk_vacina_tipo_vacina foreign key (id_tipo_vacina) references tipo_vacina(id);

/*INSERT INTO vacina(id_usuario, tipo, quem, dt_tomou, dt_validade) VALUES (1, 'H1N1', 'Pessoa 1', '2015-01-01', '2016-01-01');*/
