begin; 

CREATE TABLE anexo( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      nome text   NOT NULL  , 
      tamanho text   NOT NULL  , 
      local text   NOT NULL  , 
      tipo text   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE atividade( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      evento_id int   NOT NULL  , 
      nome text   NOT NULL  , 
      nome_en text   , 
      descricao text   , 
      descricao_en text   , 
      local_nome text   NOT NULL  , 
      local_geolocalizacao text   , 
      dt_inicio datetime   NOT NULL  , 
      dt_fim datetime   , 
 PRIMARY KEY (id)); 

 CREATE TABLE avaliacao_atividade( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      inscricao_atividade_id int   NOT NULL  , 
      estrelas double   NOT NULL  , 
      comentario text   , 
      dt_registro datetime   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE avaliacao_evento( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      inscricao_id int   NOT NULL  , 
      estrelas double   NOT NULL  , 
      comentario text   , 
      dt_registro datetime   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE evento( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      responsavel_id int   NOT NULL  , 
      nome text   NOT NULL  , 
      nome_en text   , 
      descricao text   NOT NULL  , 
      desricao_en text   , 
      banner text   , 
      logo text   , 
      cor text   , 
      dt_inicio datetime   NOT NULL  , 
      dt_fim datetime   , 
      contato_nome text   NOT NULL  , 
      contato_email text   NOT NULL  , 
      contato_telefone text   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE inscricao( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      pessoa_id int   NOT NULL  , 
      evento_id int   NOT NULL  , 
      codigo text   NOT NULL  , 
      dt_ativacao datetime   NOT NULL  , 
      dt_cancelamento datetime   , 
 PRIMARY KEY (id)); 

 CREATE TABLE inscricao_atividade( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      inscricao_id int   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE mensagem( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      inscricao_atividade_id int   NOT NULL  , 
      dt_registro datetime   NOT NULL  , 
      conteudo text   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE notificacao_atividade( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      titulo text   NOT NULL  , 
      titulo_en text   , 
      conteudo text   NOT NULL  , 
      conteudo_en text   , 
      logo text   , 
      dt_notificacao datetime   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE notificacao_evento( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      evento_id int   NOT NULL  , 
      titulo text   NOT NULL  , 
      titulo_en text   , 
      conteudo text   NOT NULL  , 
      conteudo_en text   , 
      logo text   , 
      dt_notificacao datetime   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE opcao( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      descricao text   NOT NULL  , 
      descricao_en text   , 
      valor text   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE pergunta( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      descricao text   NOT NULL  , 
      descricao_en text   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pergunta_opcao( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      pergunta_id int   NOT NULL  , 
      opcao_id int   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      nome text   NOT NULL  , 
      email text   NOT NULL  , 
      email2 text   , 
      email3 text   , 
      foto text   , 
      token text   , 
      idioma text   , 
 PRIMARY KEY (id)); 

 CREATE TABLE responsaveis_atividade( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      usuario_id int   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE resposta_pergunta( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      atividade_inscricao_id int   NOT NULL  , 
      opcao_id int   NOT NULL  , 
      pergunta_id int   NOT NULL  , 
      dt_registro datetime   NOT NULL  , 
 PRIMARY KEY (id)); 

  
 ALTER TABLE anexo ADD CONSTRAINT fk_anexo_1 FOREIGN KEY (atividade_id) references atividade(id); 
ALTER TABLE atividade ADD CONSTRAINT fk_atividade_1 FOREIGN KEY (evento_id) references evento(id); 
ALTER TABLE avaliacao_atividade ADD CONSTRAINT fk_avaliacao_atividade_1 FOREIGN KEY (inscricao_atividade_id) references inscricao_atividade(id); 
ALTER TABLE avaliacao_evento ADD CONSTRAINT fk_avaliacao_evento_1 FOREIGN KEY (inscricao_id) references inscricao(id); 
ALTER TABLE evento ADD CONSTRAINT fk_evento_1 FOREIGN KEY (responsavel_id) references pessoa(id); 
ALTER TABLE inscricao ADD CONSTRAINT fk_inscricao_2 FOREIGN KEY (evento_id) references evento(id); 
ALTER TABLE inscricao ADD CONSTRAINT fk_inscricao_1 FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE inscricao_atividade ADD CONSTRAINT fk_inscricao_atividade_2 FOREIGN KEY (inscricao_id) references inscricao(id); 
ALTER TABLE inscricao_atividade ADD CONSTRAINT fk_inscricao_atividade_1 FOREIGN KEY (atividade_id) references atividade(id); 
ALTER TABLE mensagem ADD CONSTRAINT fk_mensagem_1 FOREIGN KEY (inscricao_atividade_id) references inscricao_atividade(id); 
ALTER TABLE notificacao_atividade ADD CONSTRAINT fk_notificacao_atividade_1 FOREIGN KEY (atividade_id) references atividade(id); 
ALTER TABLE notificacao_evento ADD CONSTRAINT fk_notificacao_evento_1 FOREIGN KEY (evento_id) references evento(id); 
ALTER TABLE pergunta ADD CONSTRAINT fk_pergunta_1 FOREIGN KEY (atividade_id) references atividade(id); 
ALTER TABLE pergunta_opcao ADD CONSTRAINT fk_pergunta_opcao_2 FOREIGN KEY (opcao_id) references opcao(id); 
ALTER TABLE pergunta_opcao ADD CONSTRAINT fk_pergunta_opcao_1 FOREIGN KEY (pergunta_id) references pergunta(id); 
ALTER TABLE responsaveis_atividade ADD CONSTRAINT fk_responsaveis_atividade_1 FOREIGN KEY (atividade_id) references atividade(id); 
ALTER TABLE resposta_pergunta ADD CONSTRAINT fk_resposta_pergunta_3 FOREIGN KEY (pergunta_id) references pergunta(id); 
ALTER TABLE resposta_pergunta ADD CONSTRAINT fk_resposta_pergunta_2 FOREIGN KEY (opcao_id) references opcao(id); 
ALTER TABLE resposta_pergunta ADD CONSTRAINT fk_resposta_pergunta_1 FOREIGN KEY (atividade_inscricao_id) references inscricao_atividade(id); 
 
 commit;