
create database RestauranteYuBaiwan;
use RestauranteYuBaiwan;
/*RestauranteYuBaiwan*/
create table user(
	id int not null auto_increment primary key,
	name varchar(50),
	lastname varchar(50),
	username varchar(50),
	email varchar(255),
	password varchar(60),
	image varchar(255),
	is_active boolean default 1,
	kind int default 1,/* 1.- admin,2.- mesero, 3.- cocinero, 4.- cajero*/
	created_at datetime
);

insert into user(name,lastname,email,password,is_active,kind,created_at) value ("Administrador", "","admin","90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad",1,1,NOW());

create table category(
	id int not null auto_increment primary key,
	image varchar(255),
	name varchar(50),
	description text,
	created_at datetime
);

create table category2(
	id int not null auto_increment primary key,
	name varchar(50),
	description text
);

create table section(
	id int not null auto_increment primary key,
	name varchar(50),
	description text
);

create table giro(
	id int not null auto_increment primary key,
	name varchar(50),
	description text
);

create table city(
	id int not null auto_increment primary key,
	name varchar(50),
	description text
);

create table comun(
	id int not null auto_increment primary key,
	name varchar(50),
	description text
);

create table bank(
	id int not null auto_increment primary key,
	name varchar(50),
	description text
);

create table unit(
	id int not null auto_increment primary key,
	name varchar(50),
	description text
);

create table presentation(
	id int not null auto_increment primary key,
	name varchar(50),
	description text
);

create table t(
	id int not null auto_increment primary key,
	name varchar(50),
	is_active boolean not null default 0
);
create table p(
	id int not null auto_increment primary key,
	name varchar(50),
	is_active boolean not null default 0
);
create table d(
	id int not null auto_increment primary key,
	name varchar(50),
	is_active boolean not null default 0
);

insert into t(name,is_active) values ("Efectivo",1),("Tarjeta Debito",0),("Cheque",0);
insert into p(name,is_active) values ("Pagado",1),("Pendiente",0),("Cancelado",0);
insert into d(name,is_active) values ("Entregado",1),("Pendiente",0),("Cancelado",0);

create table item(
	id int not null auto_increment primary key,
	name varchar(50),
	capacity int,
	x float default 0,
	y float default 0,
	is_visible boolean default 1,	
	status int default 1 /** 1.- disponible, 2.- ocupada, 3.- reservada, 4.- no disponible **/
);

insert into item (name,capacity) values ("1",6),("2",6),("3",6),("4",6),("5",6),("6",6),("7",6),("8",6),("9",6),("10",6);


create table product(
	id int not null auto_increment primary key,
	image varchar(255),
	barcode varchar(50),
	name varchar(50),
	description text,
	inventary_min int default 10,
	duration int,
	price_in float,
	price_out float,
	section_id int,
	unit_id int,
	presentation_id int,
	user_id int,
	category_id int,
	category2_id int,
	created_at datetime,
	is_active boolean default 1,
	use_ingredients boolean default 0,
	use_inventory boolean default 0,
	is_ingredient boolean default 0,
	is_favorite boolean default 0,
	foreign key (user_id) references user(id)
);


create table product_ingredient(
	id int not null auto_increment primary key,
	product_id int not null,
	ingredient_id int not null,
	q float,
	is_required boolean not null,
	foreign key (product_id) references product(id),
	foreign key (ingredient_id) references product(id)
);


/*
person kind
1.- Mesero
2.- Provider
*/
create table person(
	id int not null auto_increment primary key,
	image varchar(255),
	rut varchar(255),
	name varchar(255),
	lastname varchar(50),
	address1 varchar(50),
	city_id int,
	comun_id int,
	giro_id int,

	address2 varchar(50),
	phone1 varchar(50),
	phone2 varchar(50),
	email1 varchar(50),
	email2 varchar(50),
	contact varchar(50),
	website varchar(50),
	is_company boolean default 0,
	is_active_access boolean default 0,
	kind int,
	created_at datetime
);

create table stock(
	id int not null auto_increment primary key,
	name varchar(50),
	is_principal boolean
);

insert into stock(name,is_principal) values ("Principal",1),("Almacen 1",0);

create table operation_type(
	id int not null auto_increment primary key,
	name varchar(50)
);

insert into operation_type (name) value ("entrada");
insert into operation_type (name) value ("salida");
insert into operation_type (name) value ("entrada-pendiente"); 
insert into operation_type (name) value ("salida-pendiente"); 
insert into operation_type (name) value ("devolucion");

create table box(
	id int not null auto_increment primary key,
	created_at datetime
);


create table sell(
	id int not null auto_increment primary key,
	person_id int ,
	user_id int ,
	operation_type_id int default 2,
	box_id int,
	tip double, /* propina */
	t_id int,
	p_id int,
	d_id int,
	item_id int,
	total double,
	cash double,
	iva double, /* impuesto actual del producto */
	discount double,
	is_draft boolean default 0,
	is_applied boolean default 0,
	foreign key (p_id) references p(id),
	foreign key (d_id) references d(id),
	foreign key (box_id) references box(id),
	foreign key (operation_type_id) references operation_type(id),
	foreign key (user_id) references user(id),
	foreign key (person_id) references user(id),
	created_at datetime
);

create table operation(
	id int not null auto_increment primary key,
	product_id int,
	stock_id int,
	q float,
	price_in double, /* precio actual del producto */
	price_out double, /* precio actual del producto */
	operation_type_id int,
	sell_id int,
	is_draft boolean default 0,
	created_at datetime,
	foreign key (stock_id) references stock(id),
	foreign key (product_id) references product(id),
	foreign key (operation_type_id) references operation_type(id),
	foreign key (sell_id) references sell(id)
);

create table spend(
	id int not null auto_increment primary key,
	name varchar(50),
	price double,
	box_id int,
	created_at datetime,
	foreign key(box_id) references box(id)
);


/*
configuration kind
1.- Booleano
2.- Texto
3.- numero
*/
create table configuration(
	id int not null auto_increment primary key,
	short varchar(255) unique,
	name varchar(255) unique,
	kind int,
	val varchar(255)
);
insert into configuration(short,name,kind,val) value("title","Titulo del Sistema",2,"Thunder Pro Mod PI");
insert into configuration(short,name,kind,val) value("imp-name","Nombre Impuesto",2,"IVA");
insert into configuration(short,name,kind,val) value("imp-val","Valor Impuesto (%)",2,"16");
insert into configuration(short,name,kind,val) value("currency","Simbolo de Moneda",2,"$");
insert into configuration(short,name,kind,val) value("sys_logo","Logo",4,"");
insert into configuration(short,name,kind,val) value("rest_bg","Fondo",4,"");

insert into configuration(short,name,kind,val) value("footer_pdf","Footer PDF",2,"Generado por el Sistema Thunder PRO IP v5");

insert into configuration(short,name,kind,val) value("ticket_title","Titulo Ticket",2,"Sistema de Restaurante");
insert into configuration(short,name,kind,val) value("ticket_head1","Encabezado 1 Ticket",2,"Encabezado");
insert into configuration(short,name,kind,val) value("ticket_head2","Encabezado 2 Ticket",2,"Direccion del comercio");