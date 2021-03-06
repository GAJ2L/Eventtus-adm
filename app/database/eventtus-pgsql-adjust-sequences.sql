SELECT setval('anexo_id_seq', coalesce(max(id),0) + 1, false) FROM anexo;
SELECT setval('atividade_id_seq', coalesce(max(id),0) + 1, false) FROM atividade;
SELECT setval('atividade_interacao_id_seq', coalesce(max(id),0) + 1, false) FROM atividade_interacao;
SELECT setval('avaliacao_atividade_id_seq', coalesce(max(id),0) + 1, false) FROM avaliacao_atividade;
SELECT setval('avaliacao_evento_id_seq', coalesce(max(id),0) + 1, false) FROM avaliacao_evento;
SELECT setval('evento_id_seq', coalesce(max(id),0) + 1, false) FROM evento;
SELECT setval('inscricao_id_seq', coalesce(max(id),0) + 1, false) FROM inscricao;
SELECT setval('inscricao_atividade_id_seq', coalesce(max(id),0) + 1, false) FROM inscricao_atividade;
SELECT setval('mensagem_id_seq', coalesce(max(id),0) + 1, false) FROM mensagem;
SELECT setval('notificacao_atividade_id_seq', coalesce(max(id),0) + 1, false) FROM notificacao_atividade;
SELECT setval('notificacao_evento_id_seq', coalesce(max(id),0) + 1, false) FROM notificacao_evento;
SELECT setval('opcao_id_seq', coalesce(max(id),0) + 1, false) FROM opcao;
SELECT setval('pergunta_id_seq', coalesce(max(id),0) + 1, false) FROM pergunta;
SELECT setval('pergunta_opcao_id_seq', coalesce(max(id),0) + 1, false) FROM pergunta_opcao;
SELECT setval('pessoa_id_seq', coalesce(max(id),0) + 1, false) FROM pessoa;
SELECT setval('responsaveis_atividade_id_seq', coalesce(max(id),0) + 1, false) FROM responsaveis_atividade;
SELECT setval('resposta_pergunta_id_seq', coalesce(max(id),0) + 1, false) FROM resposta_pergunta;