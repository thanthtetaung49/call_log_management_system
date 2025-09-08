<?php

namespace App\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, ShouldAutoSize, ShouldQueue, WithHeadings, WithMapping
{
    use Exportable;

    public $msisdns;
    public $startDate;
    public $endDate;

    public function __construct($msisdns = null, $startDate = null, $endDate = null)
    {
        // You can pass parameters here if needed
        $this->msisdns = $msisdns;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $conn = odbc_connect('VerticaDSN', 'dbadmin', 'vertica');

        $query = "WITH CTE_query_1 AS (
	SELECT
        day_key_p,
        chrgd_msisdn AS accs_mthd_cd,
        orgntng_nm AS called_a_party_no,
        trmntng_nm AS calling_b_party_no,
        actvty_tmstmp  AS call_time,
        actvty_drtn    AS call_duration_sec,
        orgntng_imsi_nm AS orgntng_imsi_nm,
        orgntng_imsi_nm AS orgntng_imei_nm,
        --orgntng_imei_nm AS orgntng_imei_nm,
        d.cell_site_key      AS cell_site_key,
        orgntng_cellid       AS first_cell_id,
        call_ctgry_cd        AS call_typ_cd,
        b.address            AS address,
        d.mini_cluster_name  AS geo_cnty_name,
        d.cluster_name       AS geo_city_name,
        d.region_name        AS geo_rgn_name
	FROM amlpvt2.dwb_ntwrk_rw_wrlss_vce_usg a
	LEFT OUTER JOIN amlpvt2.dwb_call_log_master_new b ON a.orgntng_cellid = b.cellid
	LEFT OUTER JOIN amlpvt2.dwb_ntwrk_cell c       ON a.orgntng_cellid =c.cell_id
	LEFT OUTER JOIN amlpvt3.vw_location_master d   ON d.cell_site_key=c.cell_site_key
	where a.day_key_p between $this->startDate and $this->endDate
		and a.chrgd_msisdn in (
			$this->msisdns
	) and orgntng_imei_nm is not null
),
CTE_query_2 as (
	SELECT
        day_key_p,
        chrgd_msisdn AS accs_mthd_cd,
        orgntng_nm AS called_a_party_no,
        trmntng_nm AS calling_b_party_no,
        actvty_tmstmp  AS call_time,
        actvty_drtn    AS call_duration_sec,
        orgntng_imsi_nm AS orgntng_imsi_nm,
        orgntng_imsi_nm AS orgntng_imei_nm,
        --orgntng_imei_nm AS orgntng_imei_nm,
        d.cell_site_key      AS cell_site_key,
        orgntng_cellid       AS first_cell_id,
        call_ctgry_cd        AS call_typ_cd,
        b.address            AS address,
        d.mini_cluster_name  AS geo_cnty_name,
        d.cluster_name       AS geo_city_name,
        d.region_name        AS geo_rgn_name
			FROM amlpvt2.dwb_ntwrk_rw_wrlss_sms_usg a
			LEFT OUTER JOIN amlpvt2.dwb_call_log_master_new b ON a.orgntng_cellid = b.cellid
			LEFT OUTER JOIN amlpvt2.dwb_ntwrk_cell c       ON a.orgntng_cellid =c.cell_id
			LEFT OUTER JOIN amlpvt3.vw_location_master d   ON d.cell_site_key=c.cell_site_key
			where a.day_key_p between $this->startDate and $this->endDate
				and a.chrgd_msisdn in (
				$this->msisdns
	) and orgntng_imei_nm is not null--order by 1 --959756699280_959782376164
),
find_duplicate_cte as (
	SELECT
		ROW_NUMBER() OVER (
			PARTITION BY called_a_party_no, TO_CHAR(call_time, 'YYYYMMDD HH24MISS') ORDER BY call_time
		) AS rn,
		*
	FROM CTE_query_2
),
CTE_modified_query_2 as (
	SELECT
--		rn,
		day_key_p,
		accs_mthd_cd,
		called_a_party_no,
		calling_b_party_no,
		call_time,
		call_duration_sec,
		orgntng_imsi_nm,
		orgntng_imei_nm,
		cell_site_key,
		first_cell_id,
		call_typ_cd,
		address,
		geo_cnty_name,
		geo_city_name,
		geo_rgn_name
	FROM find_duplicate_cte
		WHERE rn = 1
		ORDER BY day_key_p
),
CTE_combine_vcg_and_sms as (
	SELECT * FROM CTE_query_1
		UNION ALL
	SELECT * FROM CTE_modified_query_2
)
SELECT * FROM CTE_combine_vcg_and_sms ORDER BY 1, 5";

        $result = odbc_exec($conn, $query);

        $rows = [];

        while ($row = odbc_fetch_array($result)) {
            $rows[] = $row;
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return ['day_key_p', 'accs_mthd_cd', 'called_a_party_no', 'calling_b_party_no', 'call_time', 'call_duration_sec', 'orgntng_imsi_nm', 'orgntng_imei_nm', 'cell_site_key', 'first_cell_id', 'call_typ_cd', 'address', 'geo_cnty_name', 'geo_city_name', 'geo_rgn_name'];
    }

    public function map($row): array
    {
        return [
            $row['day_key_p'],
            $row['accs_mthd_cd'],
            $row['called_a_party_no'],
            $row['calling_b_party_no'],
            $row['call_time'],
            $row['call_duration_sec'],
            $row['orgntng_imsi_nm'],
            $row['orgntng_imei_nm'],
            $row['cell_site_key'],
            $row['first_cell_id'],
            $row['call_typ_cd'],
            $row['address'],
            $row['geo_cnty_name'],
            $row['geo_city_name'],
            $row['geo_rgn_name'],
        ];
    }
}
