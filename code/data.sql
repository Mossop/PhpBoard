INSERT INTO Board
(id,name,webroot,docroot,codedir,themedir,filedir,timeout,admin)
VALUES
('wswym','WSWYM Bulletin
Board','http://eeguinness.swan.ac.uk/~dave/PhpBoard','/home/dave/public_html/PhpBoard','code','theme','files',120,'dave');

INSERT INTO User
(id,password,board_id,person)
VALUES
('dave',PASSWORD('Tequila8791'),'wswym',1),
('mike',PASSWORD('nma640v'),'wswym',3),
('test',PASSWORD('test'),'wswym',2);

INSERT INTO People
(id,fullname,email,phone,nickname)
VALUES
(1,'Dave Townsend','dtownsend@iee.org','07909962336','Dave'),
(3,'Mike Brownsword','mbrownsword@iee.org','07855805669','Mike'),
(2,'Test User','flibble@iee.org','','Test');

INSERT INTO Groups
(id)
VALUES
('admin'),
('folderadmin'),
('messageadmin'),
('useradmin'),
('contactadmin'),
('boardadmin');

INSERT INTO UserGroup
(user_id,group_id)
VALUES
('dave','admin');

INSERT INTO Folder
(id,parent,board,name)
VALUES
(10,      8 ,      "wswym",   "Recruitment"),
(9 ,      8 ,      "wswym",   "Involvement"),
(8 ,      0 ,      "wswym",   "Recruitment and Retention"),
(11,      0 ,      "wswym",   "Organising Events"),
(12,      11,      "wswym",   "SPT 2002 (MB,SA)"),
(15,      11,      "wswym",   "Premiums 2002 (MB,SA)"),
(16,      11,      "wswym",   "Lifeskills 2000/2001 (JB,GE,RA,KE)"),
(17,      11,      "wswym",   "Branch Programme 2001/2002 (MB,DT,KE,NP,SA)"),
(18,      11,      "wswym",   "Egg Race"),
(19,      0 ,      "wswym",   "Schools & Education"),
(20,      19,      "wswym",   "Micromouse"),
(21,      19,      "wswym",   "Resources for Education"),
(22,      0 ,      "wswym",   "Disseminate Information"),
(23,      22,      "wswym",   "Webpages"),
(24,      22,      "wswym",   "PRO Stuff"),
(25,      0 ,      "wswym",   "Organise Self"),
(26,      25,      "wswym",   "Manage Money"),
(27,      26,      "wswym",   "Business Plan 2002/2003"),
(28,      25,      "wswym",   "Manage Committee");
