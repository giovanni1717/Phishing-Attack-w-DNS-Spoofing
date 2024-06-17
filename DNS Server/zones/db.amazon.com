$TTL	604800
@	IN	SOA	dns.amazon.com.	admin.amazon.com. (
			3			; Serial
			604800			; Refresh
			86400			; Retry
			2419200			; Expire
			604800 )		; Negative Cache TTL
;
; name servers - NS records
	IN 	NS	dns.amazon.com.

; name servers - A records
dns.amazon.com.	IN	A	192.168.160.5

; 192.168.161.0/24 - A records
amazon.com.	IN	A	192.168.161.5
