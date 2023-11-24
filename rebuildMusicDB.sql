drop database if exists musicdb;
create database musicdb;
use musicdb;

create table SongDataCategory
(
    songGenreID varchar(3) not null,
    songGenreDescription varchar(20) not null,
    primary key (songGenreID)
);

create table SongData
(
    songID int not null,
    songGenreID varchar(3) not null,
    songTitle varchar(80) not null,
    artist varchar(80) not null,
    album varchar(80) not null,
    length varchar(10) not null,
    primary key (songID),
    foreign key (songGenreID) references SongDataCategory(songGenreID) on delete cascade
);

-- SongDataCategory
insert into SongDataCategory values ('POP', 'Pop');
insert into SongDataCategory values ('HIP', 'Hip-Hop');
insert into SongDataCategory values ('PUN', 'Punk');
insert into SongDataCategory values ('IND', 'Indie');

-- SongData
insert into SongData values(101,'POP','Greedy', 'Tate McRae', 'Greedy', '2:11');
insert into SongData values(102,'POP','making the bed', 'Olivia Rodrigo', 'GUTS', '3:18');
insert into SongData values(103,'POP','Some Nights', 'fun.', 'Some Nights', '4:37');
insert into SongData values(104,'POP','Freakum Dress', 'Beyonce', 'B-Day', '3:20');
insert into SongData values(105,'POP','Anyone', 'Justin Bieber', 'Justice', '3:10');
insert into SongData values(106,'POP','Dusty', 'Ed Sheeran', '-', '3:42');
insert into SongData values(107,'POP','Anything Could Happen', 'Ellie Goulding', 'Halycon', '4:46');
insert into SongData values(108,'POP','Orphans', 'Coldplay', 'Everyday Life', '3:17');
insert into SongData values(109,'POP','Express Yourself', 'Madonna', 'Celebration', '3:59');
insert into SongData values(110,'POP','Jaded', 'Miley Cyrus', 'Endless Summer Vacation', '3:05');
insert into SongData values(201,'HIP','Bleu Snappin', 'Lil Wayne', 'Sorry 4 Tha Wait', '3:18');
insert into SongData values(202,'HIP','Like A Blade Of Grass', 'Jack Harlow', 'Come Home The Kids Miss You', '2:06');
insert into SongData values(203,'HIP','Pelle Coat', 'Lil Durk', 'Almost Healed', '4:13');
insert into SongData values(204,'HIP','Thy Motion', 'Trippie Redd', 'A Love Letter To You 5', '2:28');
insert into SongData values(205,'HIP','Still Here', 'Drake', 'Views', '3:09');
insert into SongData values(206,'HIP','Dear Mama', '2Pac', 'Me Against The World', '4:40');
insert into SongData values(207,'HIP','RENTAL', 'BROCKHAMPTON', 'SATURATION III', '3:33');
insert into SongData values(208,'HIP','Check Yo Self', 'Ice Cube', 'The Predator', '3:56');
insert into SongData values(209,'HIP','Red Ruby Da Sleeze', 'Nicki Minaj', 'Red Ruby Da Sleeze', '3:34');
insert into SongData values(210,'HIP','Shoulda', 'Kevin Gates', 'Luca Brasi 3', '2:43');
insert into SongData values(301,'IND','Yours', 'Now, Now', 'Saved', '4:11');
insert into SongData values(302,'IND','Bags', 'Clairo', 'Immunity', '4:20');
insert into SongData values(303,'IND','Kyoto', 'Poebe Bridgers', 'Punisher', '3:04');
insert into SongData values(304,'IND','Falling', 'Haim', 'Days Are Gone', '4:17');
insert into SongData values(305,'IND','Steeeam', 'Shelly', 'Shelly', '2:50');

DROP USER IF EXISTS app;
CREATE USER app IDENTIFIED BY 'app';
GRANT ALL privileges on musicdb.* TO app;
