/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     09/01/2017 08:41:10                          */
/*==============================================================*/


drop table if exists Article;

drop table if exists Comment;

/*==============================================================*/
/* Table: Article                                               */
/*==============================================================*/
create table Article
(
   arId                 int not null AUTO_INCREMENT,
   arTitle              varchar(254),
   arDescription        longtext,
   arDate               datetime,
   arLink               varchar(254),
   arDescPhoto          varchar(254),
   primary key (arId)
);

/*==============================================================*/
/* Table: Comment                                               */
/*==============================================================*/
create table Comment
(
   arId                 int not null,
   coId                 int not null AUTO_INCREMENT,
   coPseudo             varchar(254),
   coDescription        longtext,
   coDate               datetime,
   primary key (arId, coId)
);

alter table Comment add constraint FK_composeDe foreign key (arId)
      references Article (arId) on delete restrict on update restrict;

