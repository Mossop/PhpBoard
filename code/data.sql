INSERT INTO Board
(id,name,webroot,docroot,codedir,themedir,timeout,admin)
VALUES
('wswym','WSWYM Bulletin Board','http://eeguinness.swan.ac.uk/~dave/PhpBoard','/home/dave/public_html/PhpBoard','code','theme',120,'dave');

INSERT INTO User
(id,password,fullname,board_id,email,phone,nickname)
VALUES
('dave',PASSWORD('Tequila8791'),'Dave Townsend','wswym','dtownsend@iee.org','07909962336','Dave'),
('test',PASSWORD('test'),'Test User','wswym','flibble@iee.org','','Test');

INSERT INTO Groups
(id)
VALUES
('admin'),
('folderadmin'),
('messageadmin'),
('boardadmin');

INSERT INTO UserGroup
(user_id,group_id)
VALUES
('dave','admin');
