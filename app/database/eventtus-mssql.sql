begin; 

CREATE TABLE anexo( 
      id  INT IDENTITY    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
      tamanho nvarchar(max)   NOT NULL  , 
      local nvarchar(max)   NOT NULL  , 
      tipo nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE atividade( 
      id  INT IDENTITY    NOT NULL  , 
      evento_id int   NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
      nome_en nvarchar(max)   , 
      descricao nvarchar(max)   , 
      descricao_en nvarchar(max)   , 
      local_nome nvarchar(max)   NOT NULL  , 
      local_geolocalizacao nvarchar(max)   , 
      dt_inicio datetime2   NOT NULL  , 
      dt_fim datetime2   , 
 PRIMARY KEY (id)); 

 CREATE TABLE atividade_interacao( 
      id  INT IDENTITY    NOT NULL  , 
      dt_registro datetime2   NOT NULL  , 
      mensagem nvarchar(max)   NOT NULL  , 
      fl_aprovado bit   , 
      atividade_id int   NOT NULL  , 
      pessoa_id int   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE avaliacao_atividade( 
      id  INT IDENTITY    NOT NULL  , 
      inscricao_atividade_id int   NOT NULL  , 
      estrelas float   NOT NULL  , 
      comentario nvarchar(max)   , 
      dt_registro datetime2   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE avaliacao_evento( 
      id  INT IDENTITY    NOT NULL  , 
      inscricao_id int   NOT NULL  , 
      estrelas float   NOT NULL  , 
      comentario nvarchar(max)   , 
      dt_registro datetime2   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE evento( 
      id  INT IDENTITY    NOT NULL  , 
      responsavel_id int   NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
      nome_en nvarchar(max)   , 
      descricao nvarchar(max)   NOT NULL  , 
      desricao_en nvarchar(max)   , 
      banner nvarchar(max)   , 
      logo nvarchar(max)   , 
      cor nvarchar(max)   , 
      dt_inicio datetime2   NOT NULL  , 
      dt_fim datetime2   , 
      contato_nome nvarchar(max)   NOT NULL  , 
      contato_email nvarchar(max)   NOT NULL  , 
      contato_telefone nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE inscricao( 
      id  INT IDENTITY    NOT NULL  , 
      pessoa_id int   NOT NULL  , 
      evento_id int   NOT NULL  , 
      codigo nvarchar(max)   NOT NULL  , 
      dt_ativacao datetime2   NOT NULL  , 
      dt_cancelamento datetime2   , 
 PRIMARY KEY (id)); 

 CREATE TABLE inscricao_atividade( 
      id  INT IDENTITY    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      inscricao_id int   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE mensagem( 
      id  INT IDENTITY    NOT NULL  , 
      inscricao_atividade_id int   NOT NULL  , 
      dt_registro datetime2   NOT NULL  , 
      conteudo nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE notificacao_atividade( 
      id  INT IDENTITY    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      titulo nvarchar(max)   NOT NULL  , 
      titulo_en nvarchar(max)   , 
      conteudo nvarchar(max)   NOT NULL  , 
      conteudo_en nvarchar(max)   , 
      logo nvarchar(max)   , 
      dt_notificacao datetime2   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE notificacao_evento( 
      id  INT IDENTITY    NOT NULL  , 
      evento_id int   NOT NULL  , 
      titulo nvarchar(max)   NOT NULL  , 
      titulo_en nvarchar(max)   , 
      conteudo nvarchar(max)   NOT NULL  , 
      conteudo_en nvarchar(max)   , 
      logo nvarchar(max)   , 
      dt_notificacao datetime2   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE opcao( 
      id  INT IDENTITY    NOT NULL  , 
      descricao nvarchar(max)   NOT NULL  , 
      descricao_en nvarchar(max)   , 
      valor nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE pergunta( 
      id  INT IDENTITY    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      descricao nvarchar(max)   NOT NULL  , 
      descricao_en nvarchar(max)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pergunta_opcao( 
      id  INT IDENTITY    NOT NULL  , 
      pergunta_id int   NOT NULL  , 
      opcao_id int   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa( 
      id  INT IDENTITY    NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
      email nvarchar(max)   NOT NULL  , 
      email2 nvarchar(max)   , 
      email3 nvarchar(max)   , 
      foto nvarchar(max)   , 
      token nvarchar(max)   , 
      idioma nvarchar(max)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE responsaveis_atividade( 
      id  INT IDENTITY    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      usuario_id int   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE resposta_pergunta( 
      id  INT IDENTITY    NOT NULL  , 
      atividade_inscricao_id int   NOT NULL  , 
      opcao_id int   NOT NULL  , 
      pergunta_id int   NOT NULL  , 
      dt_registro datetime2   NOT NULL  , 
 PRIMARY KEY (id)); 

  
 ALTER TABLE anexo ADD CONSTRAINT fk_anexo_1 FOREIGN KEY (atividade_id) references atividade(id); 
ALTER TABLE atividade ADD CONSTRAINT fk_atividade_1 FOREIGN KEY (evento_id) references evento(id); 
ALTER TABLE atividade_interacao ADD CONSTRAINT fk_atividade_interacao_2 FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE atividade_interacao ADD CONSTRAINT fk_atividade_interacao_1 FOREIGN KEY (atividade_id) references atividade(id); 
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