--
-- PostgreSQL database dump
--

-- Dumped from database version 10.12 (Ubuntu 10.12-0ubuntu0.18.04.1)
-- Dumped by pg_dump version 10.12

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

SET default_with_oids = false;

--
-- Name: api_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.api_tokens (
    token_id bigint NOT NULL,
    server_id bigint,
    token character varying(200),
    token_type character varying(50),
    created_by character varying(15),
    creation_datetime timestamp without time zone
);


ALTER TABLE public.api_tokens OWNER TO postgres;

--
-- Name: api_tokens_token_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.api_tokens_token_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.api_tokens_token_id_seq OWNER TO postgres;

--
-- Name: api_tokens_token_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.api_tokens_token_id_seq OWNED BY public.api_tokens.token_id;


--
-- Name: cam_boxes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cam_boxes (
    uik_id bigint NOT NULL,
    cam_id character varying(100),
    boxes_flag boolean,
    boxes_num integer,
    last_update_datetime timestamp without time zone
);


ALTER TABLE public.cam_boxes OWNER TO postgres;

--
-- Name: cam_boxes_info; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cam_boxes_info (
    uik_id bigint NOT NULL,
    box_id integer,
    box_quality numeric,
    conf numeric,
    type character varying(50),
    type_conf numeric,
    normalized_dist_k numeric,
    normalized_width_k numeric,
    normalized_orientation_k numeric,
    normalized_voter_orientation_k numeric,
    visible_rate numeric,
    intersection_rate numeric,
    bbox_coords_json character varying(400),
    cap_bbox_coords_json character varying(400)
);


ALTER TABLE public.cam_boxes_info OWNER TO postgres;

--
-- Name: cam_boxes_info_uik_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cam_boxes_info_uik_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cam_boxes_info_uik_id_seq OWNER TO postgres;

--
-- Name: cam_boxes_info_uik_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cam_boxes_info_uik_id_seq OWNED BY public.cam_boxes_info.uik_id;


--
-- Name: cam_boxes_uik_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cam_boxes_uik_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cam_boxes_uik_id_seq OWNER TO postgres;

--
-- Name: cam_boxes_uik_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cam_boxes_uik_id_seq OWNED BY public.cam_boxes.uik_id;


--
-- Name: cam_ids; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cam_ids (
    uik_id bigint,
    cam_id character varying(100)
);


ALTER TABLE public.cam_ids OWNER TO postgres;

--
-- Name: cam_info; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cam_info (
    uik_id bigint,
    cam_id character varying(100),
    accepted_to_count_voters boolean,
    counting_type character varying(50),
    cam_quality numeric,
    suspicious_coef numeric,
    cam_image character varying(400),
    cam_rec_image character varying(400)
);


ALTER TABLE public.cam_info OWNER TO postgres;

--
-- Name: check_results; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.check_results (
    check_result_id bigint NOT NULL,
    event_id bigint,
    checked_by bigint,
    violation boolean
);


ALTER TABLE public.check_results OWNER TO postgres;

--
-- Name: check_results_check_result_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.check_results_check_result_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.check_results_check_result_id_seq OWNER TO postgres;

--
-- Name: check_results_check_result_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.check_results_check_result_id_seq OWNED BY public.check_results.check_result_id;


--
-- Name: check_servers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.check_servers (
    check_id bigint,
    server_id bigint
);


ALTER TABLE public.check_servers OWNER TO postgres;

--
-- Name: check_statuses; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.check_statuses (
    status_id bigint NOT NULL,
    status_name character varying(50)
);


ALTER TABLE public.check_statuses OWNER TO postgres;

--
-- Name: check_statuses_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.check_statuses_status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.check_statuses_status_id_seq OWNER TO postgres;

--
-- Name: check_statuses_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.check_statuses_status_id_seq OWNED BY public.check_statuses.status_id;


--
-- Name: check_table; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.check_table (
    check_id bigint NOT NULL,
    check_name character varying(50),
    check_status_id bigint,
    check_type_id bigint,
    data_type_id bigint,
    start_datetime timestamp without time zone,
    end_datetime timestamp without time zone,
    voting_date_start date,
    voting_date_end date,
    created_by bigint,
    time_left bigint,
    objects_processed bigint
);


ALTER TABLE public.check_table OWNER TO postgres;

--
-- Name: check_table_check_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.check_table_check_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.check_table_check_id_seq OWNER TO postgres;

--
-- Name: check_table_check_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.check_table_check_id_seq OWNED BY public.check_table.check_id;


--
-- Name: check_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.check_types (
    check_type_id bigint NOT NULL,
    type_name character varying(100)
);


ALTER TABLE public.check_types OWNER TO postgres;

--
-- Name: check_types_check_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.check_types_check_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.check_types_check_type_id_seq OWNER TO postgres;

--
-- Name: check_types_check_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.check_types_check_type_id_seq OWNED BY public.check_types.check_type_id;


--
-- Name: data_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.data_types (
    data_type_id bigint NOT NULL,
    type_name character varying(100)
);


ALTER TABLE public.data_types OWNER TO postgres;

--
-- Name: data_types_data_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.data_types_data_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.data_types_data_type_id_seq OWNER TO postgres;

--
-- Name: data_types_data_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.data_types_data_type_id_seq OWNED BY public.data_types.data_type_id;


--
-- Name: downloading_videos_auth; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.downloading_videos_auth (
    check_id bigint NOT NULL,
    auth_needed boolean,
    auth_type_id bigint,
    auth_link character varying(500),
    auth_login character varying(50),
    auth_password character varying(50)
);


ALTER TABLE public.downloading_videos_auth OWNER TO postgres;

--
-- Name: downloading_videos_auth_check_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.downloading_videos_auth_check_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.downloading_videos_auth_check_id_seq OWNER TO postgres;

--
-- Name: downloading_videos_auth_check_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.downloading_videos_auth_check_id_seq OWNED BY public.downloading_videos_auth.check_id;


--
-- Name: event_statuses; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.event_statuses (
    status_id bigint NOT NULL,
    status_name character varying(100)
);


ALTER TABLE public.event_statuses OWNER TO postgres;

--
-- Name: event_statuses_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.event_statuses_status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.event_statuses_status_id_seq OWNER TO postgres;

--
-- Name: event_statuses_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.event_statuses_status_id_seq OWNED BY public.event_statuses.status_id;


--
-- Name: event_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.event_types (
    type_id bigint NOT NULL,
    type_name character varying(100)
);


ALTER TABLE public.event_types OWNER TO postgres;

--
-- Name: event_types_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.event_types_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.event_types_type_id_seq OWNER TO postgres;

--
-- Name: event_types_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.event_types_type_id_seq OWNED BY public.event_types.type_id;


--
-- Name: events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.events (
    event_id bigint NOT NULL,
    check_id bigint,
    event_type_id integer,
    uik_id bigint,
    cam_id character varying(100),
    event_datetime timestamp without time zone,
    probability numeric,
    checked boolean,
    check_datetime timestamp without time zone,
    status_id integer
);


ALTER TABLE public.events OWNER TO postgres;

--
-- Name: events_event_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.events_event_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.events_event_id_seq OWNER TO postgres;

--
-- Name: events_event_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.events_event_id_seq OWNED BY public.events.event_id;


--
-- Name: new_check_tasks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.new_check_tasks (
    task_id bigint NOT NULL,
    task_type_id bigint,
    target_check_id bigint
);


ALTER TABLE public.new_check_tasks OWNER TO postgres;

--
-- Name: new_check_tasks_task_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.new_check_tasks_task_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.new_check_tasks_task_id_seq OWNER TO postgres;

--
-- Name: new_check_tasks_task_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.new_check_tasks_task_id_seq OWNED BY public.new_check_tasks.task_id;


--
-- Name: photos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.photos (
    photo_id bigint NOT NULL,
    check_id bigint,
    uik_id bigint,
    cam_id character varying(100),
    storage_server_id bigint,
    photo_datetime_utc timestamp without time zone,
    objects_json character varying(5000),
    source_image_path character varying(300),
    image_height integer,
    image_width integer,
    frame_number integer,
    series_number integer
);


ALTER TABLE public.photos OWNER TO postgres;

--
-- Name: photos_path; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.photos_path (
    storage_server_id bigint NOT NULL,
    images_path character varying(500),
    check_id bigint
);


ALTER TABLE public.photos_path OWNER TO postgres;

--
-- Name: photos_path_storage_server_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.photos_path_storage_server_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.photos_path_storage_server_id_seq OWNER TO postgres;

--
-- Name: photos_path_storage_server_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.photos_path_storage_server_id_seq OWNED BY public.photos_path.storage_server_id;


--
-- Name: photos_photo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.photos_photo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.photos_photo_id_seq OWNER TO postgres;

--
-- Name: photos_photo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.photos_photo_id_seq OWNED BY public.photos.photo_id;


--
-- Name: proofs_path; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.proofs_path (
    event_id bigint,
    proof character varying(300),
    rec_proof character varying(300),
    utc_datetime timestamp without time zone,
    local_datetime timestamp without time zone
);


ALTER TABLE public.proofs_path OWNER TO postgres;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    role_id bigint NOT NULL,
    role_name character varying(50)
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- Name: roles_role_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.roles_role_id_seq OWNER TO postgres;

--
-- Name: roles_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_role_id_seq OWNED BY public.roles.role_id;


--
-- Name: server_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.server_types (
    server_type_id bigint NOT NULL,
    type_desc character varying(100)
);


ALTER TABLE public.server_types OWNER TO postgres;

--
-- Name: server_types_server_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.server_types_server_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.server_types_server_type_id_seq OWNER TO postgres;

--
-- Name: server_types_server_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.server_types_server_type_id_seq OWNED BY public.server_types.server_type_id;


--
-- Name: servers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.servers (
    server_id bigint NOT NULL,
    server_type_id bigint,
    online boolean,
    password character varying(255),
    server_ip character varying(15),
    token_id bigint,
    last_response timestamp without time zone
);


ALTER TABLE public.servers OWNER TO postgres;

--
-- Name: task_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.task_types (
    task_type_id bigint NOT NULL,
    task_desc character varying(50)
);


ALTER TABLE public.task_types OWNER TO postgres;

--
-- Name: task_types_task_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.task_types_task_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.task_types_task_type_id_seq OWNER TO postgres;

--
-- Name: task_types_task_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.task_types_task_type_id_seq OWNED BY public.task_types.task_type_id;


--
-- Name: uiks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.uiks (
    uik_id bigint NOT NULL,
    check_id bigint,
    region character varying(100),
    region_number integer,
    uik_number integer,
    address character varying(500),
    boxes_type character varying(50),
    timezone_offset integer,
    voters_registered integer,
    latitude numeric,
    longitude numeric,
    utc8am bigint,
    uik_type character varying(50)
);


ALTER TABLE public.uiks OWNER TO postgres;

--
-- Name: uiks_uik_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.uiks_uik_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.uiks_uik_id_seq OWNER TO postgres;

--
-- Name: uiks_uik_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.uiks_uik_id_seq OWNED BY public.uiks.uik_id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    user_id bigint NOT NULL,
    user_login character varying(50),
    user_pass character varying(255),
    creation_date timestamp without time zone,
    created_by_user_id bigint,
    remember_token character varying(255),
    role_id bigint
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_user_id_seq OWNER TO postgres;

--
-- Name: users_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_user_id_seq OWNED BY public.users.user_id;


--
-- Name: video_processing; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.video_processing (
    video_id bigint NOT NULL,
    server_id bigint,
    progress numeric,
    time_left integer,
    processing_start_datetime timestamp without time zone,
    processing_end_datetime timestamp without time zone,
    last_processing_response timestamp without time zone
);


ALTER TABLE public.video_processing OWNER TO postgres;

--
-- Name: video_processing_video_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.video_processing_video_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.video_processing_video_id_seq OWNER TO postgres;

--
-- Name: video_processing_video_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.video_processing_video_id_seq OWNED BY public.video_processing.video_id;


--
-- Name: videos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.videos (
    video_id bigint NOT NULL,
    check_id bigint,
    direct_link character varying(400),
    uik_id bigint,
    cam_id character varying(100),
    recording_start_utc_time bigint,
    recording_length integer,
    processed boolean,
    processing boolean,
    loaded_by bigint
);


ALTER TABLE public.videos OWNER TO postgres;

--
-- Name: videos_auth_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.videos_auth_types (
    auth_type_id bigint NOT NULL,
    type_name character varying(50)
);


ALTER TABLE public.videos_auth_types OWNER TO postgres;

--
-- Name: videos_auth_types_auth_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.videos_auth_types_auth_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.videos_auth_types_auth_type_id_seq OWNER TO postgres;

--
-- Name: videos_auth_types_auth_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.videos_auth_types_auth_type_id_seq OWNED BY public.videos_auth_types.auth_type_id;


--
-- Name: videos_video_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.videos_video_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.videos_video_id_seq OWNER TO postgres;

--
-- Name: videos_video_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.videos_video_id_seq OWNED BY public.videos.video_id;


--
-- Name: violation_applicant_name; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.violation_applicant_name (
    applicant_id bigint NOT NULL,
    name character varying(150),
    is_organisation boolean
);


ALTER TABLE public.violation_applicant_name OWNER TO postgres;

--
-- Name: violation_applicant_name_applicant_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.violation_applicant_name_applicant_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.violation_applicant_name_applicant_id_seq OWNER TO postgres;

--
-- Name: violation_applicant_name_applicant_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.violation_applicant_name_applicant_id_seq OWNED BY public.violation_applicant_name.applicant_id;


--
-- Name: violation_decree; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.violation_decree (
    check_id bigint NOT NULL,
    decree character varying(200)
);


ALTER TABLE public.violation_decree OWNER TO postgres;

--
-- Name: violation_decree_check_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.violation_decree_check_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.violation_decree_check_id_seq OWNER TO postgres;

--
-- Name: violation_decree_check_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.violation_decree_check_id_seq OWNED BY public.violation_decree.check_id;


--
-- Name: violation_protocols; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.violation_protocols (
    event_id bigint NOT NULL,
    protocol_link character varying(300),
    protocol_datetime timestamp without time zone,
    second_protocol_link character varying(300),
    second_protocol_datetime timestamp without time zone,
    applicant_id bigint
);


ALTER TABLE public.violation_protocols OWNER TO postgres;

--
-- Name: violation_protocols_event_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.violation_protocols_event_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.violation_protocols_event_id_seq OWNER TO postgres;

--
-- Name: violation_protocols_event_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.violation_protocols_event_id_seq OWNED BY public.violation_protocols.event_id;


--
-- Name: violation_status_dates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.violation_status_dates (
    event_id bigint,
    status_id bigint,
    status_datetime timestamp without time zone
);


ALTER TABLE public.violation_status_dates OWNER TO postgres;

--
-- Name: votes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.votes (
    vote_id bigint NOT NULL,
    uik_id bigint,
    vote_datetime timestamp without time zone,
    vote_conf numeric
);


ALTER TABLE public.votes OWNER TO postgres;

--
-- Name: votes_official; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.votes_official (
    record_id bigint NOT NULL,
    check_id bigint,
    uik_id bigint,
    region_number integer,
    uik_number integer,
    tik_number integer,
    tik_name character varying(100),
    election_name character varying(100),
    voters_registered integer,
    voters_voted integer,
    voters_voted_at_station integer,
    voters_voted_early integer,
    voters_voted_outside_station integer,
    turnout_10 numeric,
    turnout_12 numeric,
    turnout_15 numeric,
    turnout_18 numeric,
    votes_json character varying(400),
    ballots_valid integer,
    ballots_invalid integer
);


ALTER TABLE public.votes_official OWNER TO postgres;

--
-- Name: votes_official_record_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.votes_official_record_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.votes_official_record_id_seq OWNER TO postgres;

--
-- Name: votes_official_record_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.votes_official_record_id_seq OWNED BY public.votes_official.record_id;


--
-- Name: votes_vote_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.votes_vote_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.votes_vote_id_seq OWNER TO postgres;

--
-- Name: votes_vote_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.votes_vote_id_seq OWNED BY public.votes.vote_id;


--
-- Name: api_tokens token_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.api_tokens ALTER COLUMN token_id SET DEFAULT nextval('public.api_tokens_token_id_seq'::regclass);


--
-- Name: cam_boxes uik_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cam_boxes ALTER COLUMN uik_id SET DEFAULT nextval('public.cam_boxes_uik_id_seq'::regclass);


--
-- Name: cam_boxes_info uik_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cam_boxes_info ALTER COLUMN uik_id SET DEFAULT nextval('public.cam_boxes_info_uik_id_seq'::regclass);


--
-- Name: check_results check_result_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_results ALTER COLUMN check_result_id SET DEFAULT nextval('public.check_results_check_result_id_seq'::regclass);


--
-- Name: check_statuses status_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_statuses ALTER COLUMN status_id SET DEFAULT nextval('public.check_statuses_status_id_seq'::regclass);


--
-- Name: check_table check_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_table ALTER COLUMN check_id SET DEFAULT nextval('public.check_table_check_id_seq'::regclass);


--
-- Name: check_types check_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_types ALTER COLUMN check_type_id SET DEFAULT nextval('public.check_types_check_type_id_seq'::regclass);


--
-- Name: data_types data_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_types ALTER COLUMN data_type_id SET DEFAULT nextval('public.data_types_data_type_id_seq'::regclass);


--
-- Name: downloading_videos_auth check_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.downloading_videos_auth ALTER COLUMN check_id SET DEFAULT nextval('public.downloading_videos_auth_check_id_seq'::regclass);


--
-- Name: event_statuses status_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.event_statuses ALTER COLUMN status_id SET DEFAULT nextval('public.event_statuses_status_id_seq'::regclass);


--
-- Name: event_types type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.event_types ALTER COLUMN type_id SET DEFAULT nextval('public.event_types_type_id_seq'::regclass);


--
-- Name: events event_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events ALTER COLUMN event_id SET DEFAULT nextval('public.events_event_id_seq'::regclass);


--
-- Name: new_check_tasks task_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.new_check_tasks ALTER COLUMN task_id SET DEFAULT nextval('public.new_check_tasks_task_id_seq'::regclass);


--
-- Name: photos photo_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.photos ALTER COLUMN photo_id SET DEFAULT nextval('public.photos_photo_id_seq'::regclass);


--
-- Name: photos_path storage_server_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.photos_path ALTER COLUMN storage_server_id SET DEFAULT nextval('public.photos_path_storage_server_id_seq'::regclass);


--
-- Name: roles role_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN role_id SET DEFAULT nextval('public.roles_role_id_seq'::regclass);


--
-- Name: server_types server_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.server_types ALTER COLUMN server_type_id SET DEFAULT nextval('public.server_types_server_type_id_seq'::regclass);


--
-- Name: task_types task_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.task_types ALTER COLUMN task_type_id SET DEFAULT nextval('public.task_types_task_type_id_seq'::regclass);


--
-- Name: uiks uik_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.uiks ALTER COLUMN uik_id SET DEFAULT nextval('public.uiks_uik_id_seq'::regclass);


--
-- Name: users user_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN user_id SET DEFAULT nextval('public.users_user_id_seq'::regclass);


--
-- Name: video_processing video_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.video_processing ALTER COLUMN video_id SET DEFAULT nextval('public.video_processing_video_id_seq'::regclass);


--
-- Name: videos video_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.videos ALTER COLUMN video_id SET DEFAULT nextval('public.videos_video_id_seq'::regclass);


--
-- Name: videos_auth_types auth_type_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.videos_auth_types ALTER COLUMN auth_type_id SET DEFAULT nextval('public.videos_auth_types_auth_type_id_seq'::regclass);


--
-- Name: violation_applicant_name applicant_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.violation_applicant_name ALTER COLUMN applicant_id SET DEFAULT nextval('public.violation_applicant_name_applicant_id_seq'::regclass);


--
-- Name: violation_decree check_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.violation_decree ALTER COLUMN check_id SET DEFAULT nextval('public.violation_decree_check_id_seq'::regclass);


--
-- Name: violation_protocols event_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.violation_protocols ALTER COLUMN event_id SET DEFAULT nextval('public.violation_protocols_event_id_seq'::regclass);


--
-- Name: votes vote_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.votes ALTER COLUMN vote_id SET DEFAULT nextval('public.votes_vote_id_seq'::regclass);


--
-- Name: votes_official record_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.votes_official ALTER COLUMN record_id SET DEFAULT nextval('public.votes_official_record_id_seq'::regclass);


--
-- Data for Name: api_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.api_tokens VALUES (6, NULL, NULL, NULL, NULL, NULL);
INSERT INTO public.api_tokens VALUES (1969164925978946, 54212, '747d794e8c5ed382810486dcc48e5b26b0c7af18', 'temp', 'auto', '2020-08-14 10:43:13.863138');
INSERT INTO public.api_tokens VALUES (1189339691641838, 54212, '939b325b1b1b588abfbfc464dddd549163dadd01', 'temp', 'auto', '2020-08-14 10:45:24.762901');
INSERT INTO public.api_tokens VALUES (3027136541899693, 54212, '0380f9d1d6fb575e804ef9e7468771b849ca1724', 'temp', 'auto', '2020-08-14 10:45:25.987099');
INSERT INTO public.api_tokens VALUES (4511464066084355, 54212, 'f4420d570000c4b535c3ff13790831c5fee756f9', 'temp', 'auto', '2020-08-14 10:46:06.978969');
INSERT INTO public.api_tokens VALUES (2144140080146902, 54212, 'be856549c3a31b71c7825acb86dde699964b65bf', 'temp', 'auto', '2020-08-14 10:47:33.391068');
INSERT INTO public.api_tokens VALUES (1076170229138277, 54212, '598ae1975904d680d50080156c71c7a9a0773bdb', 'temp', 'auto', '2020-08-14 10:47:44.811066');
INSERT INTO public.api_tokens VALUES (1998622630755239, 54212, 'ec24bcca15842158673411afe8684653733a7c5c', 'temp', 'auto', '2020-08-14 10:47:53.407587');
INSERT INTO public.api_tokens VALUES (3057569722243516, 54212, '048f5eee91784af169945241727b5d984452e99d', 'temp', 'auto', '2020-08-14 10:48:03.126934');
INSERT INTO public.api_tokens VALUES (1986525901162005, 54212, '02b8ecad5a9760352a86ad6f3ac80727a36fe4df', 'temp', 'auto', '2020-08-14 10:48:58.534896');
INSERT INTO public.api_tokens VALUES (2378556494626248, 54212, '69aa4f0ebcea0a4f9c078c6160af9a86c1a3cb33', 'temp', 'auto', '2020-08-14 10:49:06.886989');
INSERT INTO public.api_tokens VALUES (1772992396270717, 54212, '549a7a7cea48b363caca4775e700bb80bc651a36', 'temp', 'auto', '2020-08-14 10:49:32.178934');
INSERT INTO public.api_tokens VALUES (1031087788979032, 54212, '5a7d9438c7be3456c0b0a79a69f774663e527b3d', 'temp', 'auto', '2020-08-14 10:54:45.640727');
INSERT INTO public.api_tokens VALUES (2596352988677360, 54212, '13c225fef3a8edc96e58a6b994d888d73dea1ef8', 'temp', 'auto', '2020-08-14 10:55:06.075022');
INSERT INTO public.api_tokens VALUES (2887887188258120, 54212, '3dceee42e8eb548279ddeb43fdedfce846ca9386', 'temp', 'auto', '2020-08-14 10:55:21.548938');
INSERT INTO public.api_tokens VALUES (1117733329813253, 54212, '580388b2b859664c134f6cec5d3da3e14119c032', 'temp', 'auto', '2020-08-14 10:56:34.278942');
INSERT INTO public.api_tokens VALUES (2342412032031420, 54212, '4fc55276d33b338942295a047a4e18aae07801dd', 'temp', 'auto', '2020-08-14 10:57:07.526964');
INSERT INTO public.api_tokens VALUES (1248350880463830, 54212, '4a29effa34f99696bc1aca7cb38791291b629105', 'temp', 'auto', '2020-08-14 11:00:28.301321');
INSERT INTO public.api_tokens VALUES (1812368773051057, 54212, '7f58eb4486efd3dbd264b9e0de8258b4d53a99c2', 'temp', 'auto', '2020-08-14 11:00:53.981008');
INSERT INTO public.api_tokens VALUES (4521346110385983, 54212, '0597054f7f17f9ac78b576ebf4275e14ade20622', 'temp', 'auto', '2020-08-14 11:01:05.975089');
INSERT INTO public.api_tokens VALUES (1817733288090013, 54212, 'c6ebcb8ae5c5c6d7a084a2ac9ff6b803898894bd', 'temp', 'auto', '2020-08-14 11:05:16.98298');
INSERT INTO public.api_tokens VALUES (4343125556525087, 54212, '74913e39a641336eda7ef16d4a59a149d5bcd0f2', 'temp', 'auto', '2020-08-14 11:05:24.22173');
INSERT INTO public.api_tokens VALUES (2830908691019986, 54212, 'd2427edd31fb81057e333d0b76634a90329a23a5', 'temp', 'auto', '2020-08-14 11:05:44.940906');
INSERT INTO public.api_tokens VALUES (3018401645723441, 54212, '7eb12a49e884eb80677d7d4f08a8f58f51c05d25', 'temp', 'auto', '2020-08-14 11:07:01.406985');
INSERT INTO public.api_tokens VALUES (7394195407635875, 54212, 'a593acf52452ea590f72952cba745706e8b430b5', 'temp', 'auto', '2020-08-14 11:09:20.387441');
INSERT INTO public.api_tokens VALUES (1893991280347070, 54212, '5d4425bcc84c965c4d1bfd52915463e34e2fd367', 'temp', 'auto', '2020-08-14 11:15:47.48476');
INSERT INTO public.api_tokens VALUES (8541136449699867, 54212, '1d4db86f9a642a23b568f9878bc8c1446101d8f2', 'temp', 'auto', '2020-08-14 11:16:00.033842');
INSERT INTO public.api_tokens VALUES (2990934075035239, 54212, '0847f58af970d44640e99ba35a68741986f6067a', 'temp', 'auto', '2020-08-14 11:18:07.51897');
INSERT INTO public.api_tokens VALUES (1444921871766647, 54212, '594443c07c1d9aead3d41e5545d464a09f38930a', 'temp', 'auto', '2020-08-14 11:18:40.441283');
INSERT INTO public.api_tokens VALUES (1586588477081889, 54212, '85f39d47b43e1d2e6ba835f63e7c4124a75a9980', 'temp', 'auto', '2020-08-14 12:21:08.418903');
INSERT INTO public.api_tokens VALUES (1615560051355922, 54212, '9144216f46f3db69937da43c94fc676c5acf1fc0', 'temp', 'auto', '2020-08-14 12:21:19.710977');
INSERT INTO public.api_tokens VALUES (1108552847976255, 54212, 'b8337e11d3f6b2eebe5f475f23f6eedeafa78d2c', 'temp', 'auto', '2020-08-14 12:21:29.751712');
INSERT INTO public.api_tokens VALUES (1123493218396335, 54212, 'c03ee80d97992188c726a16a81a306e6b4c66ccc', 'temp', 'auto', '2020-08-14 12:25:00.267099');
INSERT INTO public.api_tokens VALUES (1422859371475104, 54212, '0be2696917083de73119d25ea894ed8d5fb4d4c4', 'temp', 'auto', '2020-08-14 12:27:47.684633');
INSERT INTO public.api_tokens VALUES (2415216949080197, 54212, '46f2955d188f344a3bcc603aa009915efa90b66f', 'temp', 'auto', '2020-08-14 12:27:49.968062');
INSERT INTO public.api_tokens VALUES (2356276242624116, 54212, '3a3c9fb0884541441fcbf501e6dce32b0bb916bf', 'temp', 'auto', '2020-08-14 12:28:42.650945');
INSERT INTO public.api_tokens VALUES (4091289379046375, 54212, '8af25d85c7e73bb57bde4554e8efcab43584dffa', 'temp', 'auto', '2020-08-14 12:28:45.229098');
INSERT INTO public.api_tokens VALUES (8210269712207690, 54212, 'b62fd0e9476341f0de826a23c7fddaae8265f4c6', 'temp', 'auto', '2020-08-14 12:39:11.406981');
INSERT INTO public.api_tokens VALUES (3551295159255209, 54212, 'ef30f1a51e3e65ae9381c7fe2ef000716d10755f', 'temp', 'auto', '2020-08-14 12:39:16.338961');
INSERT INTO public.api_tokens VALUES (1128287647957104, 54212, 'b5a2e62057cff1babf7a901c36b198dd0c39e36f', 'temp', 'auto', '2020-08-14 12:39:58.574918');


--
-- Data for Name: cam_boxes; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.cam_boxes VALUES (2, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 2, '2020-04-21 14:02:26.277006');
INSERT INTO public.cam_boxes VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 2, '2020-04-21 14:02:26.277006');


--
-- Data for Name: cam_boxes_info; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.cam_boxes_info VALUES (2, 1, 0.512, 0.889521, 'ballot_box', 0.752, 0.254, 0.658, 0.2475, 0.2844753, 0.569, 0.21454, '{''x1'': 0.123, ''y2'': 0.123}', '{''x1'': 0.123, ''y2'': 0.123}');
INSERT INTO public.cam_boxes_info VALUES (1, 1, 0.512, 0.889521, 'ballot_box', 0.752, 0.254, 0.658, 0.2475, 0.2844753, 0.569, 0.21454, '{"x1": 0.123, "y2": 0.123}', '{"x1": 0.123, "y2": 0.123}');


--
-- Data for Name: cam_ids; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.cam_ids VALUES (1, 'sdasdg12-dasvc124');
INSERT INTO public.cam_ids VALUES (1, 'gsdv3qs3-hsdv4jh4');
INSERT INTO public.cam_ids VALUES (2, 'cvxa9fdx-xcz1ska2');
INSERT INTO public.cam_ids VALUES (2, '9jjgfhdd-ntuhhtet');


--
-- Data for Name: cam_info; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (2, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', false, 'approximate', 0.687, 0.313, 'img/1/uiks/74/257/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_2de23495.jpg', 'img/1/uiks/74/257/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_dd509333.jpg');
INSERT INTO public.cam_info VALUES (2, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub333', false, 'approximate', 0.687, 0.313, 'img/1/uiks/74/257/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub333/raw_cd8673d5.jpg', 'img/1/uiks/74/257/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub333/rec_7a63294d.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');
INSERT INTO public.cam_info VALUES (1, '09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub', true, 'approximate', 0.687, 0.313, 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/raw_941364ac.jpg', 'img/1/uiks/74/123/09626109-864a-aaaa-aaaa-bcad28d0c9ae-sub/rec_aeccad0e.jpg');


--
-- Data for Name: check_results; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: check_servers; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: check_statuses; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.check_statuses VALUES (1, 'Обработка');
INSERT INTO public.check_statuses VALUES (2, 'Проверка завершена');
INSERT INTO public.check_statuses VALUES (3, 'Проверка остановлена');
INSERT INTO public.check_statuses VALUES (4, 'Инициализация');


--
-- Data for Name: check_table; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.check_table VALUES (1, 'Проверка 12345', 1, 2, 4, '2020-07-03 16:51:47', '2020-07-05 19:51:47', '2020-07-01', '2020-07-01', 1, 0, 652720);


--
-- Data for Name: check_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.check_types VALUES (1, 'Оффлайн по фото');
INSERT INTO public.check_types VALUES (2, 'Оффлайн по видео');
INSERT INTO public.check_types VALUES (3, 'Онлайн по видео');


--
-- Data for Name: data_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.data_types VALUES (1, 'Нарезанные фото');
INSERT INTO public.data_types VALUES (2, 'Онлайн видеопоток');
INSERT INTO public.data_types VALUES (3, 'Видео одним файлом');
INSERT INTO public.data_types VALUES (4, 'Видео частями');
INSERT INTO public.data_types VALUES (5, 'Видео частями в архиве');


--
-- Data for Name: downloading_videos_auth; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.downloading_videos_auth VALUES (1, true, 1, 'https://google.com', 'admin', 'password');


--
-- Data for Name: event_statuses; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.event_statuses VALUES (1, 'Новое событие');
INSERT INTO public.event_statuses VALUES (2, 'Не нарушение');
INSERT INTO public.event_statuses VALUES (3, 'Новое нарушение');
INSERT INTO public.event_statuses VALUES (4, 'Создан протокол');
INSERT INTO public.event_statuses VALUES (5, 'Получен ответ');
INSERT INTO public.event_statuses VALUES (6, 'Повторное нарушение');
INSERT INTO public.event_statuses VALUES (7, 'Нарушение исправлено');
INSERT INTO public.event_statuses VALUES (8, 'Повторное нарушение исправлено');
INSERT INTO public.event_statuses VALUES (9, 'Нарушение не исправлено');
INSERT INTO public.event_statuses VALUES (10, 'Повторное нарушение не исправлено');


--
-- Data for Name: event_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.event_types VALUES (1, 'Нет ящиков');
INSERT INTO public.event_types VALUES (2, 'Перемещение ящиков');
INSERT INTO public.event_types VALUES (3, 'Не хватает ящиков');


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.events VALUES (1, 1, 1, 1, 'sdasdg12-dasvc124', '2020-07-01 20:51:47', 0.784, false, NULL, 1);
INSERT INTO public.events VALUES (2, 1, 2, 2, '9jjgfhdd-ntuhhtet', '2020-07-01 20:54:33', 0.654, false, NULL, 1);
INSERT INTO public.events VALUES (3, 2, 4, 10745, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (4, 2, 4, 10745, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (5, 2, 4, 10745, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (6, 2, 4, 10745, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (7, 2, 4, 10745, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (8, 2, 4, 10745, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (9, 2, 4, 10745, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (10, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (11, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (12, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (13, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (14, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (15, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (16, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (17, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (18, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (19, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (20, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (21, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (22, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (23, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (24, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (25, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (26, 2, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.758, false, NULL, 1);
INSERT INTO public.events VALUES (27, 1, 4, 1, 'abc123', '2020-04-21 16:45:58.277006', 0.123123, false, NULL, 1);
INSERT INTO public.events VALUES (28, 1, 4, 1, 'abc123', '2020-04-21 16:45:58.277006', 0.123123, false, NULL, 1);
INSERT INTO public.events VALUES (29, 1, 4, 1, 'abc123', '2020-04-21 16:45:58.277006', 0.123123, false, NULL, 1);
INSERT INTO public.events VALUES (30, 1, 4, 1, 'abc123', '2020-04-21 16:45:58.277006', 0.123123, false, NULL, 1);
INSERT INTO public.events VALUES (31, 1, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (32, 1, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (33, 1, 4, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (34, 1, 7, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (35, 1, 6, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (36, 1, 6, 1, '09161092-387a-aaaa-aaaa-64db8b377cd1-sub', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (37, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (38, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (39, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (40, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (41, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (42, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (43, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (44, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (45, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (46, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (47, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (48, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (49, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (50, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (51, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (52, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (53, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (54, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (55, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (56, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (57, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (58, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (59, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (60, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (61, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (62, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (63, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (64, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (65, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (66, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (67, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (68, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (69, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (70, 1, 6, 1, '1', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (71, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (72, 1, 6, 1, '1', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (73, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (74, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (75, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (76, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (77, 123, 6, 123, 'abc', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (78, 123, 6, 123, 'abc', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (79, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (80, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (81, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (82, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (83, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (84, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (85, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (86, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (87, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (88, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (89, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (90, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (91, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (92, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (93, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (94, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (95, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (96, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (97, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (98, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (99, 1, 4, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 0.7123, false, NULL, 1);
INSERT INTO public.events VALUES (100, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (101, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (102, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (103, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (104, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);
INSERT INTO public.events VALUES (105, 1, 6, 1, 'sdasdg12-dasvc124', '2020-04-21 16:45:58.277006', 123.0, false, NULL, 1);


--
-- Data for Name: new_check_tasks; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: photos; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: photos_path; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: proofs_path; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.proofs_path VALUES (1, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_hjfsdgs.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_kijuhygt.jpg', '2020-01-14 14:54:47', '2020-07-01 20:51:47');
INSERT INTO public.proofs_path VALUES (1, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_htgdt4gd.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_vgbhnjgt.jpg', '2020-01-14 14:55:47', '2020-07-01 20:52:47');
INSERT INTO public.proofs_path VALUES (2, 'img/1/proofs/74/257/9jjgfhdd-ntuhhtet/raw_ujyhtgrf.jpg', 'img/1/proofs/74/257/9jjgfhdd-ntuhhtet/rec_dcfrtgyh.jpg', '2020-01-14 12:54:47', '2020-07-01 20:54:33');
INSERT INTO public.proofs_path VALUES (2, 'img/1/proofs/74/257/9jjgfhdd-ntuhhtet/raw_ikmjnyhg.jpg', 'img/1/proofs/74/257/9jjgfhdd-ntuhhtet/rec_xswqazxs.jpg', '2020-01-14 12:55:47', '2020-07-01 20:55:33');
INSERT INTO public.proofs_path VALUES (NULL, 'vid/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/raw_69762ebd.mp4', 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/rec_edd5497b.jpg', '2020-04-21 14:02:26.277006', '2020-04-21 14:02:26.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'vid/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/raw_5c0963b3.mp4', 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/rec_a4a84682.jpg', '2020-04-21 14:02:26.277006', '2020-04-21 14:02:26.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'vid/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/raw_9c9c138a.mp4', 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/rec_fbc56c6c.jpg', '2020-04-21 14:02:26.277006', '2020-04-21 14:02:26.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/abc123/raw_a4d72b56.jpg', 'img/1/proofs/74/123/abc123/rec_a174e667.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 08:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/abc123/raw_d6cd6258.jpg', 'img/1/proofs/74/123/abc123/rec_dd8fee2d.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 08:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/abc123/raw_a327e9e6.jpg', 'img/1/proofs/74/123/abc123/rec_9d0bc713.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 08:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/raw_3016829d.jpg', 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/rec_127798c0.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/raw_6b6f574e.jpg', 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/rec_b076dad1.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/raw_d4aca7e1.jpg', 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/rec_d18bc0a9.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/raw_ab540f1e.jpg', 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/rec_cb1419ea.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/raw_da38bd29.jpg', 'img/1/proofs/74/123/09161092-387a-aaaa-aaaa-64db8b377cd1-sub/rec_d2290994.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_be8494de.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_108ab5e8.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_f30a0912.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_13b7fbcd.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_7d389afc.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_6754675e.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_f3928755.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_cec303c9.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_b4df0c22.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_1c1af7d3.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_579d7399.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_22231bc2.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_5ae7d943.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_4a0952be.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_3472b553.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_1ebd827e.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_85e311f7.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_32bab125.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_a16e1d8b.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_9031d771.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_9a522f94.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_0335f6bc.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_28ddbb5d.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_747fb0fc.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_8be9f076.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_62e3a421.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_222c0873.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_2414bf28.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_a2efcabc.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_bb5d9bf8.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_653b9e66.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_74b5b0b4.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_439e2e25.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_3b2d1981.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_99dedbad.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_95fe9cd2.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_d3ef79d7.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_1df25f0e.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_b0265370.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_06f3f2c7.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_708225ad.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_905ba390.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_dcbe5f02.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_b97b70e3.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_563bf841.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_72b88c51.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_9ac21013.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_7e6c3531.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_0be000b3.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_b2aa029b.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_d5cb07c9.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_aece6e02.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_fa767271.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_bc6e554c.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_56ab7e16.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_3f6e672f.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_72ccf0f6.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_b5335386.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_1bb0ebcb.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_4d3848c5.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_db24c440.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_83422ec0.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_7ad31175.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_82e3e694.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_d9a8b2e4.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_8c3a04ad.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/1/raw_749ab95f.jpg', 'img/1/proofs/74/123/1/rec_c1959dcf.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_f8aa7b92.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_1e6691d4.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/1/raw_4feb2bfd.jpg', 'img/1/proofs/74/123/1/rec_d3782df9.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_e2b1b974.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_ddc0aeb1.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_727bab9e.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_ba40f0ce.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_137b7d55.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_cdfcf18e.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_d416c9cb.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_d3af25a3.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_f2ccdd86.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_aea3800d.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_a2008ca7.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_2d2ebf9c.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_90f094e7.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_00ce5b32.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_4c1d8cba.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_f752779b.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_1fc08525.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_a6c4cf05.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_abe0516a.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_006c8d44.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_b99b65b7.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_a9525353.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_fdff252a.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_feade357.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_5db1bb2a.jpg', 'vid/1/proofs/74/123/sdasdg12-dasvc124/rec_cfe735b0.mp4', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_10f241dc.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_ee991478.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_be03cc99.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_ad9c5c97.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_3c2ddbf2.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_21280822.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_0ba525c4.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_074728d5.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_5c7677dc.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_8a5a8190.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_7af79cf6.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_f7cd9807.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_474b245c.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_7fd6386b.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_179465c9.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_649073d9.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_4007a529.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_5f905c5e.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_cf785fd8.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_fb46c630.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_7cc7c627.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_6ad0a7db.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_3b94f6ae.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_8c29e2c3.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_1658adbe.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_733c97d6.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_6f261faa.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_fce1a964.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_b8b0e9cf.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_c723fe7d.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');
INSERT INTO public.proofs_path VALUES (NULL, 'img/1/proofs/74/123/sdasdg12-dasvc124/raw_a977c8da.jpg', 'img/1/proofs/74/123/sdasdg12-dasvc124/rec_49257fd6.jpg', '2020-04-21 08:45:58.277006', '2020-04-21 16:45:58.277006');


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.roles VALUES (1, 'Администратор');
INSERT INTO public.roles VALUES (2, 'Менеджер');
INSERT INTO public.roles VALUES (3, 'Волонтёр');


--
-- Data for Name: server_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.server_types VALUES (1, 'Сервер распознавания ящиков');
INSERT INTO public.server_types VALUES (2, 'Сервер хранения данных');
INSERT INTO public.server_types VALUES (3, 'Главный сервер');
INSERT INTO public.server_types VALUES (4, 'Сервер распознавания видеопотока');


--
-- Data for Name: servers; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.servers VALUES (12345, 1, false, '179ad45c6ce2cb97cf1029e212046e81', '123.44.77.88', 3133800120852342, '2020-08-03 22:20:22.366964');
INSERT INTO public.servers VALUES (98765, 3, false, '179ad45c6ce2cb97cf1029e212046e81', '123.44.11.22', 1399893199471596, '2020-08-03 22:35:51.446439');
INSERT INTO public.servers VALUES (54212, 2, false, '179ad45c6ce2cb97cf1029e212046e81', '123.44.99.10', 2411659948835585, '2020-08-18 11:44:17.524028');


--
-- Data for Name: task_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.task_types VALUES (1, 'Новая проверка');
INSERT INTO public.task_types VALUES (2, 'Остановить проверку');
INSERT INTO public.task_types VALUES (3, 'Продолжить проверку');
INSERT INTO public.task_types VALUES (4, 'Удалить проверку');


--
-- Data for Name: uiks; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.uiks VALUES (1, 1, 'Челябинская область', 74, 123, 'ул. Ленина 123', 'ballot_box', 180, 2145, 55.785496, 61.785496, 1536469200, 'uik');
INSERT INTO public.uiks VALUES (2, 1, 'Челябинская область', 74, 257, 'ул. Ленина 98', 'koib', 180, 3452, 55.284496, 61.655396, 1536469200, 'uik');


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.users VALUES (1, 'admin', '179ad45c6ce2cb97cf1029e212046e81', '2020-01-01 16:51:47', NULL, '', 1);


--
-- Data for Name: video_processing; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.video_processing VALUES (520, 54212, 0.425796, 214, '2020-08-14 12:39:58.918352', NULL, '2020-08-14 12:40:04.338541');


--
-- Data for Name: videos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.videos VALUES (520, 1, 'https://google.com/vid1_1.mp4', 1, 'sdasdg12-dasvc124', 1536469200, 900, false, false, 1);


--
-- Data for Name: videos_auth_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.videos_auth_types VALUES (1, 'Проверим WebCam');


--
-- Data for Name: violation_applicant_name; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.violation_applicant_name VALUES (1, 'Иванов Иван Иванович', false);
INSERT INTO public.violation_applicant_name VALUES (2, 'Семёнов Семён Семёнович', false);


--
-- Data for Name: violation_decree; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.violation_decree VALUES (1, 'п. 12 ст. 2 Постановление ЦИК РФ "О чём-нибудь" №12 от 21.03.2020 г.');


--
-- Data for Name: violation_protocols; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: violation_status_dates; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: votes; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.votes VALUES (1, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (2, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (3, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (4, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (5, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (6, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (7, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (8, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (9, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (10, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (11, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (12, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (13, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (14, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (15, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (16, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (17, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (18, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (19, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (20, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (21, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (22, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (23, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (24, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (25, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (26, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (27, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (28, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (29, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (30, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (31, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (32, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (33, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (34, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (35, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (36, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (37, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (38, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (39, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (40, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (41, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (42, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (43, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (44, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (45, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (46, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (47, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (48, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (49, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (50, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (51, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (52, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (53, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (54, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (55, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (56, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (57, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (58, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (59, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (60, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (61, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (62, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (63, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (64, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (65, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (66, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (67, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (68, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (69, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (70, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (71, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (72, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (73, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (74, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (75, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (76, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (77, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (78, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (79, 1, '2020-04-21 15:24:03.277006', 0.762);
INSERT INTO public.votes VALUES (80, 1, '2020-04-21 15:24:03.277006', 0.762);


--
-- Data for Name: votes_official; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: api_tokens_token_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.api_tokens_token_id_seq', 6, true);


--
-- Name: cam_boxes_info_uik_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cam_boxes_info_uik_id_seq', 1, false);


--
-- Name: cam_boxes_uik_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cam_boxes_uik_id_seq', 1, false);


--
-- Name: check_results_check_result_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.check_results_check_result_id_seq', 1, false);


--
-- Name: check_statuses_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.check_statuses_status_id_seq', 1, false);


--
-- Name: check_table_check_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.check_table_check_id_seq', 2, false);


--
-- Name: check_types_check_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.check_types_check_type_id_seq', 1, false);


--
-- Name: data_types_data_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.data_types_data_type_id_seq', 1, false);


--
-- Name: downloading_videos_auth_check_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.downloading_videos_auth_check_id_seq', 1, false);


--
-- Name: event_statuses_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.event_statuses_status_id_seq', 1, false);


--
-- Name: event_types_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.event_types_type_id_seq', 1, false);


--
-- Name: events_event_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.events_event_id_seq', 105, true);


--
-- Name: new_check_tasks_task_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.new_check_tasks_task_id_seq', 1, false);


--
-- Name: photos_path_storage_server_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.photos_path_storage_server_id_seq', 1, false);


--
-- Name: photos_photo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.photos_photo_id_seq', 1, false);


--
-- Name: roles_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_role_id_seq', 1, false);


--
-- Name: server_types_server_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.server_types_server_type_id_seq', 1, false);


--
-- Name: task_types_task_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.task_types_task_type_id_seq', 1, false);


--
-- Name: uiks_uik_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.uiks_uik_id_seq', 2, true);


--
-- Name: users_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_user_id_seq', 1, false);


--
-- Name: video_processing_video_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.video_processing_video_id_seq', 1, false);


--
-- Name: videos_auth_types_auth_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.videos_auth_types_auth_type_id_seq', 1, false);


--
-- Name: videos_video_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.videos_video_id_seq', 512, true);


--
-- Name: violation_applicant_name_applicant_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.violation_applicant_name_applicant_id_seq', 1, false);


--
-- Name: violation_decree_check_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.violation_decree_check_id_seq', 1, false);


--
-- Name: violation_protocols_event_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.violation_protocols_event_id_seq', 1, false);


--
-- Name: votes_official_record_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.votes_official_record_id_seq', 1, false);


--
-- Name: votes_vote_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.votes_vote_id_seq', 80, true);


--
-- Name: api_tokens api_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.api_tokens
    ADD CONSTRAINT api_tokens_pkey PRIMARY KEY (token_id);


--
-- Name: cam_boxes_info cam_boxes_info_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cam_boxes_info
    ADD CONSTRAINT cam_boxes_info_pkey PRIMARY KEY (uik_id);


--
-- Name: cam_boxes cam_boxes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cam_boxes
    ADD CONSTRAINT cam_boxes_pkey PRIMARY KEY (uik_id);


--
-- Name: check_results check_results_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_results
    ADD CONSTRAINT check_results_pkey PRIMARY KEY (check_result_id);


--
-- Name: check_statuses check_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_statuses
    ADD CONSTRAINT check_statuses_pkey PRIMARY KEY (status_id);


--
-- Name: check_table check_table_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_table
    ADD CONSTRAINT check_table_pkey PRIMARY KEY (check_id);


--
-- Name: check_types check_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_types
    ADD CONSTRAINT check_types_pkey PRIMARY KEY (check_type_id);


--
-- Name: data_types data_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.data_types
    ADD CONSTRAINT data_types_pkey PRIMARY KEY (data_type_id);


--
-- Name: downloading_videos_auth downloading_videos_auth_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.downloading_videos_auth
    ADD CONSTRAINT downloading_videos_auth_pkey PRIMARY KEY (check_id);


--
-- Name: event_statuses event_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.event_statuses
    ADD CONSTRAINT event_statuses_pkey PRIMARY KEY (status_id);


--
-- Name: event_types event_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.event_types
    ADD CONSTRAINT event_types_pkey PRIMARY KEY (type_id);


--
-- Name: events events_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT events_pkey PRIMARY KEY (event_id);


--
-- Name: new_check_tasks new_check_tasks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.new_check_tasks
    ADD CONSTRAINT new_check_tasks_pkey PRIMARY KEY (task_id);


--
-- Name: photos_path photos_path_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.photos_path
    ADD CONSTRAINT photos_path_pkey PRIMARY KEY (storage_server_id);


--
-- Name: photos photos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.photos
    ADD CONSTRAINT photos_pkey PRIMARY KEY (photo_id);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (role_id);


--
-- Name: server_types server_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.server_types
    ADD CONSTRAINT server_types_pkey PRIMARY KEY (server_type_id);


--
-- Name: servers servers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_pkey PRIMARY KEY (server_id);


--
-- Name: task_types task_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.task_types
    ADD CONSTRAINT task_types_pkey PRIMARY KEY (task_type_id);


--
-- Name: uiks uiks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.uiks
    ADD CONSTRAINT uiks_pkey PRIMARY KEY (uik_id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (user_id);


--
-- Name: video_processing video_processing_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.video_processing
    ADD CONSTRAINT video_processing_pkey PRIMARY KEY (video_id);


--
-- Name: videos_auth_types videos_auth_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.videos_auth_types
    ADD CONSTRAINT videos_auth_types_pkey PRIMARY KEY (auth_type_id);


--
-- Name: videos videos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.videos
    ADD CONSTRAINT videos_pkey PRIMARY KEY (video_id);


--
-- Name: violation_applicant_name violation_applicant_name_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.violation_applicant_name
    ADD CONSTRAINT violation_applicant_name_pkey PRIMARY KEY (applicant_id);


--
-- Name: violation_decree violation_decree_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.violation_decree
    ADD CONSTRAINT violation_decree_pkey PRIMARY KEY (check_id);


--
-- Name: violation_protocols violation_protocols_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.violation_protocols
    ADD CONSTRAINT violation_protocols_pkey PRIMARY KEY (event_id);


--
-- Name: votes_official votes_official_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.votes_official
    ADD CONSTRAINT votes_official_pkey PRIMARY KEY (record_id);


--
-- Name: votes votes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.votes
    ADD CONSTRAINT votes_pkey PRIMARY KEY (vote_id);


--
-- PostgreSQL database dump complete
--

