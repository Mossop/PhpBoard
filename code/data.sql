INSERT INTO Board
(id,name,webroot,docroot,codedir,themedir,timeout,admin)
VALUES
('wswym','Wales South West Younger Members Board','http://localhost/~dave/phpboard','/home/dave/public_html/phpboard','code','theme',120,'dave');

INSERT INTO User
(id,password,fullname,board_id,email,nickname)
VALUES
('dave',PASSWORD('Tequila8791'),'Dave Townsend','wswym','dtownsend@iee.org','Dave');

INSERT INTO Groups
(id)
VALUES
('admin');

INSERT INTO UserGroup
(user_id,group_id)
VALUES
('dave','admin');
