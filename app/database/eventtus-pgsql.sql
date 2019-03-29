begin; 

CREATE TABLE anexo( 
      id  SERIAL    NOT NULL  , 
      atividade_id integer   NOT NULL  , 
      nome text   NOT NULL  , 
      tamanho text   NOT NULL  , 
      local text   NOT NULL  , 
      tipo text   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE atividade( 
      id  SERIAL    NOT NULL  , 
      evento_id integer   NOT NULL  , 
      nome text   NOT NULL  , 
      nome_en text   , 
      descricao text   , 
      descricao_en text   , 
      local_nome text   NOT NULL  , 
      local_geolocalizacao text   , 
      dt_inicio timestamp   NOT NULL  , 
      dt_fim timestamp   , 
 PRIMARY KEY (id)); 

 CREATE TABLE avaliacao_atividade( 
      id  SERIAL    NOT NULL  , 
      inscricao_atividade_id integer   NOT NULL  , 
      estrelas float   NOT NULL  , 
      comentario text   , 
      dt_registro timestamp   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE avaliacao_evento( 
      id  SERIAL    NOT NULL  , 
      inscricao_id integer   NOT NULL  , 
      estrelas float   NOT NULL  , 
      comentario text   , 
      dt_registro timestamp   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE evento( 
      id  SERIAL    NOT NULL  , 
      responsavel_id integer   NOT NULL  , 
      nome text   NOT NULL  , 
      nome_en text   , 
      descricao text   NOT NULL  , 
      desricao_en text   , 
      banner text   , 
      logo text   , 
      cor text   , 
      dt_inicio timestamp   NOT NULL  , 
      dt_fim timestamp   , 
      contato_nome text   NOT NULL  , 
      contato_email text   NOT NULL  , 
      contato_telefone text   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE inscricao( 
      id  SERIAL    NOT NULL  , 
      pessoa_id integer   NOT NULL  , 
      evento_id integer   NOT NULL  , 
      codigo text   NOT NULL  , 
      dt_ativacao timestamp   NOT NULL  , 
      dt_cancelamento timestamp   , 
 PRIMARY KEY (id)); 

 CREATE TABLE inscricao_atividade( 
      id  SERIAL    NOT NULL  , 
      atividade_id integer   NOT NULL  , 
      inscricao_id integer   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE mensagem( 
      id  SERIAL    NOT NULL  , 
      inscricao_atividade_id integer   NOT NULL  , 
      dt_registro timestamp   NOT NULL  , 
      conteudo text   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE notificacao_atividade( 
      id  SERIAL    NOT NULL  , 
      atividade_id integer   NOT NULL  , 
      titulo text   NOT NULL  , 
      titulo_en text   , 
      conteudo text   NOT NULL  , 
      conteudo_en text   , 
      logo text   , 
      dt_notificacao timestamp   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE notificacao_evento( 
      id  SERIAL    NOT NULL  , 
      evento_id integer   NOT NULL  , 
      titulo text   NOT NULL  , 
      titulo_en text   , 
      conteudo text   NOT NULL  , 
      conteudo_en text   , 
      logo text   , 
      dt_notificacao timestamp   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE opcao( 
      id  SERIAL    NOT NULL  , 
      descricao text   NOT NULL  , 
      descricao_en text   , 
      valor text   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE pergunta( 
      id  SERIAL    NOT NULL  , 
      atividade_id integer   NOT NULL  , 
      descricao text   NOT NULL  , 
      descricao_en text   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pergunta_opcao( 
      id  SERIAL    NOT NULL  , 
      pergunta_id integer   NOT NULL  , 
      opcao_id integer   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
      email text   NOT NULL  , 
      email2 text   , 
      email3 text   , 
      foto text   , 
      token text   , 
      idioma text   , 
 PRIMARY KEY (id)); 

 CREATE TABLE responsaveis_atividade( 
      id  SERIAL    NOT NULL  , 
      atividade_id integer   NOT NULL  , 
      usuario_id integer   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE resposta_pergunta( 
      id  SERIAL    NOT NULL  , 
      atividade_inscricao_id integer   NOT NULL  , 
      opcao_id integer   NOT NULL  , 
      pergunta_id integer   NOT NULL  , 
      dt_registro timestamp   NOT NULL  , 
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