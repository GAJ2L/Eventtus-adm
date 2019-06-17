INSERT INTO pessoa (nome,email,email2,email3,foto,"token",idioma) VALUES 
('Daiane Hensel','daihensel@gmail.com',NULL,NULL,NULL,NULL,'pt');

INSERT INTO evento (responsavel_id,nome,nome_en,descricao,desricao_en,banner,logo,cor,dt_inicio,dt_fim,contato_nome,contato_email,contato_telefone) VALUES 
(1,'Evento 1',NULL,'evento tt',NULL,NULL,NULL,'#c42e2e','2019-10-01 00:00:00.000','2019-10-06 00:00:00.000','Daiane','daihensel@gmail.com','(51)99898-1655');

INSERT INTO atividade (evento_id,nome,nome_en,descricao,descricao_en,local_nome,local_geolocalizacao,dt_inicio,dt_fim) VALUES 
(1,'palestra 1',NULL,NULL,NULL,'sala 1',NULL,'2019-10-01 19:30:00.000',NULL),
(1,'palestra 2',NULL,NULL,NULL,'sala 2',NULL,'2019-10-02 08:45:00.000',NULL);

INSERT INTO atividade_interacao (dt_registro,mensagem,fl_aprovado,atividade_id,pessoa_id) VALUES 
(now(),'É na sola da bota?',false,(SELECT max(a.id) FROM atividade a), (SELECT max(p.id) FROM pessoa p)),
(now(),'É na palma da bota?',false,(SELECT max(a.id) FROM atividade a), (SELECT max(p.id) FROM pessoa p)),
(now(),'É na sola da bota e na palma da bota?',false,(SELECT max(a.id) FROM atividade a), (SELECT max(p.id) FROM pessoa p));

