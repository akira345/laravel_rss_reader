--
-- PostgreSQL database dump
--

-- Dumped from database version 12.3 (Debian 12.3-1.pgdg100+1)
-- Dumped by pg_dump version 12.3 (Debian 12.3-1.pgdg90+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categories (
    id integer NOT NULL,
    user_id integer NOT NULL,
    category character varying(512) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN categories.user_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.categories.user_id IS '登録ユーザID';


--
-- Name: COLUMN categories.category; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.categories.category IS 'カテゴリ名';


--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: login_histories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.login_histories (
    id integer NOT NULL,
    user_id integer NOT NULL,
    memo character varying(255) NOT NULL,
    ipaddr inet NOT NULL,
    user_agent text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN login_histories.user_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.login_histories.user_id IS '登録ユーザID';


--
-- Name: COLUMN login_histories.memo; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.login_histories.memo IS '備考';


--
-- Name: COLUMN login_histories.ipaddr; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.login_histories.ipaddr IS 'アクセス元IPアドレス';


--
-- Name: COLUMN login_histories.user_agent; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.login_histories.user_agent IS 'ユーザエージェント';


--
-- Name: login_histories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.login_histories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: login_histories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.login_histories_id_seq OWNED BY public.login_histories.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: rss_datas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.rss_datas (
    id integer NOT NULL,
    user_id integer NOT NULL,
    rss_url character varying(2000) NOT NULL,
    comment character varying(512) NOT NULL,
    category_id integer,
    keywords text NOT NULL,
    ad_deny_flg boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN rss_datas.user_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_datas.user_id IS '登録ユーザID';


--
-- Name: COLUMN rss_datas.rss_url; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_datas.rss_url IS 'RSS URL';


--
-- Name: COLUMN rss_datas.comment; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_datas.comment IS 'コメント';


--
-- Name: COLUMN rss_datas.category_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_datas.category_id IS 'カテゴリID';


--
-- Name: COLUMN rss_datas.keywords; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_datas.keywords IS '配信キーワード';


--
-- Name: COLUMN rss_datas.ad_deny_flg; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_datas.ad_deny_flg IS '広告拒否フラグ';


--
-- Name: rss_datas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.rss_datas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: rss_datas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.rss_datas_id_seq OWNED BY public.rss_datas.id;


--
-- Name: rss_delivery_attributes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.rss_delivery_attributes (
    id integer NOT NULL,
    rss_id integer NOT NULL,
    deliv_flg boolean NOT NULL,
    repeat_deliv_deny_flg boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN rss_delivery_attributes.rss_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_delivery_attributes.rss_id IS 'RSS_ID';


--
-- Name: COLUMN rss_delivery_attributes.deliv_flg; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_delivery_attributes.deliv_flg IS 'メール配信フラグ';


--
-- Name: COLUMN rss_delivery_attributes.repeat_deliv_deny_flg; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_delivery_attributes.repeat_deliv_deny_flg IS '再配送拒否フラグ';


--
-- Name: rss_delivery_attributes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.rss_delivery_attributes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: rss_delivery_attributes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.rss_delivery_attributes_id_seq OWNED BY public.rss_delivery_attributes.id;


--
-- Name: rss_view_attributes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.rss_view_attributes (
    id integer NOT NULL,
    rss_id integer NOT NULL,
    rss_contents_list_cnt smallint DEFAULT 0 NOT NULL,
    hidden_flg boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN rss_view_attributes.rss_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_view_attributes.rss_id IS 'RSS_ID';


--
-- Name: COLUMN rss_view_attributes.rss_contents_list_cnt; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_view_attributes.rss_contents_list_cnt IS 'RSS記事表示数';


--
-- Name: COLUMN rss_view_attributes.hidden_flg; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.rss_view_attributes.hidden_flg IS '非表示フラグ';


--
-- Name: rss_view_attributes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.rss_view_attributes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: rss_view_attributes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.rss_view_attributes_id_seq OWNED BY public.rss_view_attributes.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    last_login_at timestamp(0) without time zone
);


--
-- Name: COLUMN users.last_login_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.users.last_login_at IS '最終ログイン';


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: wk_send_rss_datas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.wk_send_rss_datas (
    id integer NOT NULL,
    user_id integer NOT NULL,
    rss_id integer NOT NULL,
    title text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN wk_send_rss_datas.user_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.wk_send_rss_datas.user_id IS '登録ユーザID';


--
-- Name: COLUMN wk_send_rss_datas.rss_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.wk_send_rss_datas.rss_id IS 'RSS ID';


--
-- Name: COLUMN wk_send_rss_datas.title; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.wk_send_rss_datas.title IS '配信済みRSSタイトル';


--
-- Name: wk_send_rss_datas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.wk_send_rss_datas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: wk_send_rss_datas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.wk_send_rss_datas_id_seq OWNED BY public.wk_send_rss_datas.id;


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: login_histories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.login_histories ALTER COLUMN id SET DEFAULT nextval('public.login_histories_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: rss_datas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_datas ALTER COLUMN id SET DEFAULT nextval('public.rss_datas_id_seq'::regclass);


--
-- Name: rss_delivery_attributes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_delivery_attributes ALTER COLUMN id SET DEFAULT nextval('public.rss_delivery_attributes_id_seq'::regclass);


--
-- Name: rss_view_attributes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_view_attributes ALTER COLUMN id SET DEFAULT nextval('public.rss_view_attributes_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: wk_send_rss_datas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.wk_send_rss_datas ALTER COLUMN id SET DEFAULT nextval('public.wk_send_rss_datas_id_seq'::regclass);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: login_histories login_histories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.login_histories
    ADD CONSTRAINT login_histories_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: rss_datas rss_datas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_datas
    ADD CONSTRAINT rss_datas_pkey PRIMARY KEY (id);


--
-- Name: rss_delivery_attributes rss_delivery_attributes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_delivery_attributes
    ADD CONSTRAINT rss_delivery_attributes_pkey PRIMARY KEY (id);


--
-- Name: rss_delivery_attributes rss_delivery_attributes_rss_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_delivery_attributes
    ADD CONSTRAINT rss_delivery_attributes_rss_id_unique UNIQUE (rss_id);


--
-- Name: rss_view_attributes rss_view_attributes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_view_attributes
    ADD CONSTRAINT rss_view_attributes_pkey PRIMARY KEY (id);


--
-- Name: rss_view_attributes rss_view_attributes_rss_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_view_attributes
    ADD CONSTRAINT rss_view_attributes_rss_id_unique UNIQUE (rss_id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: wk_send_rss_datas wk_send_rss_datas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.wk_send_rss_datas
    ADD CONSTRAINT wk_send_rss_datas_pkey PRIMARY KEY (id);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: categories categories_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: rss_datas rss_datas_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_datas
    ADD CONSTRAINT rss_datas_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id);


--
-- Name: rss_datas rss_datas_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_datas
    ADD CONSTRAINT rss_datas_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: rss_delivery_attributes rss_delivery_attributes_rss_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_delivery_attributes
    ADD CONSTRAINT rss_delivery_attributes_rss_id_foreign FOREIGN KEY (rss_id) REFERENCES public.rss_datas(id) ON DELETE CASCADE;


--
-- Name: rss_view_attributes rss_view_attributes_rss_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rss_view_attributes
    ADD CONSTRAINT rss_view_attributes_rss_id_foreign FOREIGN KEY (rss_id) REFERENCES public.rss_datas(id) ON DELETE CASCADE;


--
-- Name: wk_send_rss_datas wk_send_rss_datas_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.wk_send_rss_datas
    ADD CONSTRAINT wk_send_rss_datas_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

INSERT INTO public.migrations VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO public.migrations VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO public.migrations VALUES (3, '2018_09_01_105521_create_categories_table', 1);
INSERT INTO public.migrations VALUES (4, '2018_09_01_105634_create_login_histories_table', 1);
INSERT INTO public.migrations VALUES (5, '2018_09_01_105703_create_rss_datas_table', 1);
INSERT INTO public.migrations VALUES (6, '2018_09_01_105720_create_wk_send_rss_datas_table', 1);
INSERT INTO public.migrations VALUES (7, '2018_09_06_120315_create_rss_delivery_attributes_table', 1);
INSERT INTO public.migrations VALUES (8, '2018_09_06_120318_create_rss_view_attributes_table', 1);
INSERT INTO public.migrations VALUES (9, '2018_09_06_120501_modify_rss_datas_table', 1);
INSERT INTO public.migrations VALUES (10, '2018_09_07_154216_add_column_last_login_at_users_table', 1);
INSERT INTO public.migrations VALUES (11, '2018_09_10_192205_modify_login_histries_table', 1);
INSERT INTO public.migrations VALUES (12, '2018_09_14_224418_modify_rss_view_attributes', 1);
INSERT INTO public.migrations VALUES (13, '2018_09_14_224527_modify_rss_delivery_attributes', 1);
INSERT INTO public.migrations VALUES (14, '2018_09_15_001016_modify_rss_view_attributes2', 1);
