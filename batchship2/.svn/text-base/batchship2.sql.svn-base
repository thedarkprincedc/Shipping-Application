--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: postgres
--

CREATE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO postgres;

SET search_path = public, pg_catalog;

--
-- Name: uuid(); Type: FUNCTION; Schema: public; Owner: bmosley
--

CREATE FUNCTION uuid() RETURNS uuid
    LANGUAGE c STRICT
    AS '$libdir/uuid-ossp', 'uuid_generate_v1';


ALTER FUNCTION public.uuid() OWNER TO bmosley;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: batchship_items; Type: TABLE; Schema: public; Owner: bmosley; Tablespace: 
--

CREATE TABLE batchship_items (
    id integer NOT NULL,
    store_name text,
    store_number text,
    address1 text,
    address2 text,
    address3 text,
    city text,
    state_province text,
    postal_code text,
    country text,
    attn text,
    phone_number text,
    dimensions text,
    insurance text,
    misc_reference_1 text,
    misc_reference_2 text,
    misc_reference_3 text,
    misc_reference_4 text,
    misc_reference_5 text,
    thirdparty_company text,
    thirdparty_street text,
    thirdparty_country text,
    thirdparty_zip text,
    thirdparty_city text,
    thirdparty_account text,
    kit_number text,
    weight text,
    thirdparty_statep text,
    batch_id integer,
    verified boolean DEFAULT false,
    verify_data text,
    shipping_method text,
    po_number text,
    job_number text,
    verified_date date,
    verify_state text DEFAULT false,
    multiplier text DEFAULT 0,
    package_type text
);


ALTER TABLE public.batchship_items OWNER TO bmosley;

--
-- Name: batchship_items_id_seq; Type: SEQUENCE; Schema: public; Owner: bmosley
--

CREATE SEQUENCE batchship_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.batchship_items_id_seq OWNER TO bmosley;

--
-- Name: batchship_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: bmosley
--

ALTER SEQUENCE batchship_items_id_seq OWNED BY batchship_items.id;


--
-- Name: event_log; Type: TABLE; Schema: public; Owner: bmosley; Tablespace: 
--

CREATE TABLE event_log (
    id integer NOT NULL,
    subject text,
    description text,
    batch_id numeric,
    user_id numeric,
    "timestamp" time with time zone
);


ALTER TABLE public.event_log OWNER TO bmosley;

--
-- Name: event_log_id_seq; Type: SEQUENCE; Schema: public; Owner: bmosley
--

CREATE SEQUENCE event_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.event_log_id_seq OWNER TO bmosley;

--
-- Name: event_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: bmosley
--

ALTER SEQUENCE event_log_id_seq OWNED BY event_log.id;


--
-- Name: import_queue; Type: TABLE; Schema: public; Owner: bmosley; Tablespace: 
--

CREATE TABLE import_queue (
    filename text NOT NULL,
    uploaded_timestamp timestamp with time zone,
    processed_timestamp timestamp with time zone,
    processed boolean DEFAULT false,
    company_name text,
    id integer NOT NULL,
    user_id integer,
    filepath text,
    fcp_jobnumber text,
    filepathjson text,
    archivepath text,
    lastmodified timestamp without time zone,
    lastmodifiedby text,
    uuid uuid,
    seqid text
);


ALTER TABLE public.import_queue OWNER TO bmosley;

--
-- Name: import_queue_id_seq; Type: SEQUENCE; Schema: public; Owner: bmosley
--

CREATE SEQUENCE import_queue_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.import_queue_id_seq OWNER TO bmosley;

--
-- Name: import_queue_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: bmosley
--

ALTER SEQUENCE import_queue_id_seq OWNED BY import_queue.id;


--
-- Name: notifications; Type: TABLE; Schema: public; Owner: bmosley; Tablespace: 
--

CREATE TABLE notifications (
    id integer NOT NULL,
    subject text,
    description text,
    batch_id integer,
    user_id integer
);


ALTER TABLE public.notifications OWNER TO bmosley;

--
-- Name: notifications_id_seq; Type: SEQUENCE; Schema: public; Owner: bmosley
--

CREATE SEQUENCE notifications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.notifications_id_seq OWNER TO bmosley;

--
-- Name: notifications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: bmosley
--

ALTER SEQUENCE notifications_id_seq OWNED BY notifications.id;


--
-- Name: thirdpartyaddress; Type: TABLE; Schema: public; Owner: bmosley; Tablespace: 
--

CREATE TABLE thirdpartyaddress (
    id integer NOT NULL,
    company_name text,
    street text,
    city text,
    state text,
    zipcode text,
    created time with time zone,
    modified time with time zone,
    modified_by text,
    verified boolean DEFAULT false,
    verified_timestamp time with time zone,
    verify_state text DEFAULT false,
    country text,
    shippingaccount text,
    verify_data text
);


ALTER TABLE public.thirdpartyaddress OWNER TO bmosley;

--
-- Name: thirdpartyaddress_id_seq; Type: SEQUENCE; Schema: public; Owner: bmosley
--

CREATE SEQUENCE thirdpartyaddress_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.thirdpartyaddress_id_seq OWNER TO bmosley;

--
-- Name: thirdpartyaddress_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: bmosley
--

ALTER SEQUENCE thirdpartyaddress_id_seq OWNED BY thirdpartyaddress.id;


--
-- Name: thirdpartyshippingaccount; Type: TABLE; Schema: public; Owner: bmosley; Tablespace: 
--

CREATE TABLE thirdpartyshippingaccount (
    id integer NOT NULL,
    service_name text,
    service_account_number text,
    thirdpartyaddressid integer
);


ALTER TABLE public.thirdpartyshippingaccount OWNER TO bmosley;

--
-- Name: thirdpartyshippingaccount_id_seq; Type: SEQUENCE; Schema: public; Owner: bmosley
--

CREATE SEQUENCE thirdpartyshippingaccount_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.thirdpartyshippingaccount_id_seq OWNER TO bmosley;

--
-- Name: thirdpartyshippingaccount_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: bmosley
--

ALTER SEQUENCE thirdpartyshippingaccount_id_seq OWNED BY thirdpartyshippingaccount.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: bmosley; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    username text,
    first_name text,
    last_name text,
    email_address text,
    privaleges text,
    registered boolean DEFAULT false
);


ALTER TABLE public.users OWNER TO bmosley;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: bmosley
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO bmosley;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: bmosley
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: v_batchship_req; Type: VIEW; Schema: public; Owner: bmosley
--

CREATE VIEW v_batchship_req AS
    SELECT batchship_items.batch_id, sum(CASE batchship_items.verified WHEN true THEN 1 ELSE 0 END) AS validation_passed, sum(CASE batchship_items.verified WHEN true THEN 0 ELSE 1 END) AS validation_failed, count(batchship_items.verified) AS total, import_queue.fcp_jobnumber, import_queue.company_name FROM (batchship_items LEFT JOIN import_queue ON ((import_queue.id = batchship_items.batch_id))) GROUP BY batchship_items.batch_id, import_queue.fcp_jobnumber, import_queue.company_name;


ALTER TABLE public.v_batchship_req OWNER TO bmosley;

--
-- Name: v_batchship_req2; Type: VIEW; Schema: public; Owner: bmosley
--

CREATE VIEW v_batchship_req2 AS
    SELECT batchship_items.batch_id, sum(CASE batchship_items.verify_state WHEN 'passed'::text THEN 1 ELSE 0 END) AS validation_passed, sum(CASE batchship_items.verify_state WHEN 'not_passed'::text THEN 1 ELSE 0 END) AS validation_notpassed, sum(CASE batchship_items.verify_state WHEN 'error'::text THEN 1 ELSE 0 END) AS validation_failed, count(batchship_items.verified) AS total, import_queue.fcp_jobnumber, import_queue.company_name FROM (batchship_items LEFT JOIN import_queue ON ((import_queue.id = batchship_items.batch_id))) GROUP BY batchship_items.batch_id, import_queue.fcp_jobnumber, import_queue.company_name;


ALTER TABLE public.v_batchship_req2 OWNER TO bmosley;

--
-- Name: v_import_queue; Type: VIEW; Schema: public; Owner: bmosley
--

CREATE VIEW v_import_queue AS
    SELECT import_queue.filename, import_queue.uploaded_timestamp, import_queue.processed, import_queue.processed_timestamp, import_queue.company_name, import_queue.id, import_queue.user_id, import_queue.filepath, import_queue.filepathjson, import_queue.fcp_jobnumber, import_queue.lastmodified, sum(CASE batchship_items.verified WHEN true THEN 1 ELSE 0 END) AS validation_passed, sum(CASE import_queue.processed WHEN true THEN CASE batchship_items.verified WHEN true THEN 0 ELSE 1 END ELSE 0 END) AS validation_failed, count(batchship_items.*) AS total_addresses, users.username FROM ((import_queue LEFT JOIN batchship_items ON ((import_queue.id = batchship_items.batch_id))) LEFT JOIN users ON ((import_queue.user_id = users.id))) GROUP BY import_queue.filename, import_queue.uploaded_timestamp, import_queue.processed, import_queue.processed_timestamp, import_queue.company_name, import_queue.id, import_queue.user_id, import_queue.filepath, import_queue.filepathjson, import_queue.fcp_jobnumber, import_queue.lastmodified, users.username;


ALTER TABLE public.v_import_queue OWNER TO bmosley;

--
-- Name: v_import_queue_old; Type: VIEW; Schema: public; Owner: bmosley
--

CREATE VIEW v_import_queue_old AS
    SELECT import_queue.filename, import_queue.uploaded_timestamp, import_queue.processed, import_queue.processed_timestamp, import_queue.company_name, import_queue.id, import_queue.user_id, import_queue.filepath, import_queue.filepathjson, import_queue.fcp_jobnumber, sum(CASE batchship_items.verified WHEN true THEN 1 ELSE 0 END) AS validation_passed, sum(CASE import_queue.processed WHEN true THEN CASE batchship_items.verified WHEN true THEN 0 ELSE 1 END ELSE 0 END) AS validation_failed, count(batchship_items.*) AS total_addresses, users.username FROM ((import_queue LEFT JOIN batchship_items ON ((import_queue.id = batchship_items.batch_id))) LEFT JOIN users ON ((import_queue.user_id = users.id))) GROUP BY import_queue.filename, import_queue.uploaded_timestamp, import_queue.processed, import_queue.processed_timestamp, import_queue.company_name, import_queue.id, import_queue.user_id, import_queue.filepath, import_queue.filepathjson, import_queue.fcp_jobnumber, users.username;


ALTER TABLE public.v_import_queue_old OWNER TO bmosley;

--
-- Name: v_thirdpartyaddress; Type: VIEW; Schema: public; Owner: bmosley
--

CREATE VIEW v_thirdpartyaddress AS
    SELECT thpa.id, thpa.company_name AS companyname, thpa.street, thpa.city, thpa.zipcode, thpa.state, thpa.country, thpa.verified, thpsa.service_name AS carrier, thpsa.service_account_number AS accountnumber FROM (thirdpartyaddress thpa LEFT JOIN thirdpartyshippingaccount thpsa ON ((thpsa.thirdpartyaddressid = thpa.id)));


ALTER TABLE public.v_thirdpartyaddress OWNER TO bmosley;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: bmosley
--

ALTER TABLE batchship_items ALTER COLUMN id SET DEFAULT nextval('batchship_items_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: bmosley
--

ALTER TABLE event_log ALTER COLUMN id SET DEFAULT nextval('event_log_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: bmosley
--

ALTER TABLE import_queue ALTER COLUMN id SET DEFAULT nextval('import_queue_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: bmosley
--

ALTER TABLE notifications ALTER COLUMN id SET DEFAULT nextval('notifications_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: bmosley
--

ALTER TABLE thirdpartyaddress ALTER COLUMN id SET DEFAULT nextval('thirdpartyaddress_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: bmosley
--

ALTER TABLE thirdpartyshippingaccount ALTER COLUMN id SET DEFAULT nextval('thirdpartyshippingaccount_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: bmosley
--

ALTER TABLE users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Name: import_queue_pk; Type: CONSTRAINT; Schema: public; Owner: bmosley; Tablespace: 
--

ALTER TABLE ONLY import_queue
    ADD CONSTRAINT import_queue_pk PRIMARY KEY (id);


--
-- Name: thirdpartyaddress_id_pk; Type: CONSTRAINT; Schema: public; Owner: bmosley; Tablespace: 
--

ALTER TABLE ONLY thirdpartyaddress
    ADD CONSTRAINT thirdpartyaddress_id_pk PRIMARY KEY (id);


--
-- Name: users_pk; Type: CONSTRAINT; Schema: public; Owner: bmosley; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pk PRIMARY KEY (id);


--
-- Name: import_queue_id_index; Type: INDEX; Schema: public; Owner: bmosley; Tablespace: 
--

CREATE UNIQUE INDEX import_queue_id_index ON import_queue USING btree (id);


--
-- Name: users_id_index; Type: INDEX; Schema: public; Owner: bmosley; Tablespace: 
--

CREATE INDEX users_id_index ON users USING btree (id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

