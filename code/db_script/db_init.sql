-- we don't know how to generate root <with-no-name> (class Root) :(
grant alter, alter routine, create, create routine, create tablespace, create temporary tables, create user, create view, delete, delete history, drop, event, execute, file, index, insert, lock tables, process, references, reload, replication client, replication slave, select, show databases, show view, shutdown, super, trigger, update, grant option on *.* to root@'127.0.0.1';

grant alter, alter routine, create, create routine, create tablespace, create temporary tables, create user, create view, delete, delete history, drop, event, execute, file, index, insert, lock tables, process, references, reload, replication client, replication slave, select, show databases, show view, shutdown, super, trigger, update, grant option on *.* to root@'::1';

grant alter, alter routine, create, create routine, create tablespace, create temporary tables, create user, create view, delete, delete history, drop, event, execute, file, index, insert, lock tables, process, references, reload, replication client, replication slave, select, show databases, show view, shutdown, super, trigger, update, grant option on *.* to root@localhost;

create table end_stat
(
    GAME_ID          bigint     not null
        primary key,
    WIN              tinyint(1) null,
    MOST_DMG_GS_ID   bigint     null,
    MOST_DEATH_GS_ID bigint     null,
    MOST_KILL_GS_ID  bigint     null
);

create table end_vote
(
    ID           bigint auto_increment
        primary key,
    VOTING_GS_ID bigint      not null,
    VOTED_GS_ID  bigint      not null,
    ROLE         varchar(20) null
);

create table game
(
    ID       bigint auto_increment
        primary key,
    PARTY_ID bigint      not null,
    STATUT   varchar(20) not null,
    TYPE     varchar(20) not null
);

create table game_session
(
    ID            bigint auto_increment
        primary key,
    GAME_ID       bigint               not null,
    SESSION_ID    bigint               not null,
    ROLE          varchar(20)          null,
    POINTS        int                  null,
    VOTED         tinyint(1) default 0 not null,
    ROLE_ADD_INFO varchar(20)          null,
    NICKNAME      varchar(255)         null
);

create table party
(
    ID             bigint auto_increment
        primary key,
    CODE           varchar(10)          not null,
    ACTIVE         tinyint(1) default 0 not null,
    DYING_DATE     date                 null,
    ACTIVE_GAME_ID bigint               null
);

create table session
(
    ID       bigint auto_increment
        primary key,
    TOKEN    varchar(255)         null,
    NICKNAME varchar(255)         null,
    PARTY_ID bigint               not null,
    POINTS   int                  null,
    ADMIN    tinyint(1) default 0 not null
);