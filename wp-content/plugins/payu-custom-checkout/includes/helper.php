<?php 

class payu_helper{

	public function getErrorText($text){

		$statuses = [

			'PAYMENT_NETWORK_REJECTED' => 'Transacción rechazada por entidad financiera',
			'ENTITY_DECLINED' => 'Transacción rechazada por el banco',
			'INSUFFICIENT_FUNDS' => 'Fondos insuficientes',
			'INVALID_CARD' => 'Tarjeta inválida',
			'CONTACT_THE_ENTITY' => 'Contactar entidad financiera',
			'BANK_ACCOUNT_ACTIVATION_ERROR' => 'Débito automático no permitido',
			'BANK_ACCOUNT_NOT_AUTHORIZED_FOR_AUTOMATIC_DEBIT' => 'Débito automático no permitido',
			'INVALID_AGENCY_BANK_ACCOUNT' => 'Débito automático no permitido',
			'INVALID_BANK_ACCOUNT' => 'Débito automático no permitido',
			'INVALID_BANK' => 'Débito automático no permitido',
			'EXPIRED_CARD' => 'Tarjeta vencida',
			'RESTRICTED_CARD' => 'Tarjeta restringida',
			'INVALID_EXPIRATION_DATE_OR_SECURITY_CODE' => 'Fecha de expiración o código de seguridadinválidos',
			'REPEAT_TRANSACTION' => 'Reintentar pago',
			'INVALID_TRANSACTION' => 'Transacción inválida',
			'EXCEEDED_AMOUNT' => 'El valor excede el máximo permitido por la entidad',
			'ABANDONED_TRANSACTION' => 'Transacción abandonada por el pagador',
			'CREDIT_CARD_NOT_AUTHORIZED_FOR_INTERNET_TRANSACTIONS' => 'Tarjeta no autorizada para comprar por internet',
			'ANTIFRAUD_REJECTED' => 'Transacción rechazada por sospecha de fraude',
			'DIGITAL_CERTIFICATE_NOT_FOUND' => 'Certificado digital no encotnrado',
			'BANK_UNREACHABLE' => 'Error tratando de cominicarse con el banco',
			'ENTITY_MESSAGING_ERROR' => 'Error comunicándose con la entidad financiera',
			'NOT_ACCEPTED_TRANSACTION' => 'Transacción no permitida al tarjetahabiente',
			'INTERNAL_PAYMENT_PROVIDER_ERROR' => 'Ocurrio un error',
			'INACTIVE_PAYMENT_PROVIDER' => 'Ocurrio un error',
			'ERROR' => 'Ocurrio un error',
			'ERROR_CONVERTING_TRANSACTION_AMOUNTS' => 'Ocurrio un error',
			'BANK_ACCOUNT_ACTIVATION_ERROR' => 'Ocurrio un error',
			'FIX_NOT_REQUIRED' => 'Ocurrio un error',
			'AUTOMATICALLY_FIXED_AND_SUCCESS_REVERSAL' => 'Ocurrio un error',
			'AUTOMATICALLY_FIXED_AND_UNSUCCESS_REVERSAL' => 'Ocurrio un error',
			'AUTOMATIC_FIXED_NOT_SUPPORTED' => 'Ocurrio un error',
			'NOT_FIXED_FOR_ERROR_STATE' => 'Ocurrio un error',
			'ERROR_FIXING_AND_REVERSING' => 'Ocurrio un error',
			'ERROR_FIXING_INCOMPLETE_DATA' => 'Ocurrio un error',
			'PAYMENT_NETWORK_BAD_RESPONSE' => 'Ocurrio un error',
			'PAYMENT_NETWORK_NO_CONNECTION' => 'No fue posible establecer comunicación con la entidad financiera',
			'PAYMENT_NETWORK_NO_RESPONSE' => 'No se recibió respuesta de la entidad financiera',
			'EXPIRED_TRANSACTION' => 'Transacción expirada',
			'PENDING_TRANSACTION_REVIEW' => 'Transacción en validación manual',
			'PENDING_TRANSACTION_CONFIRMATION' => 'Recibo de pago generado. En espera de pago',
			'PENDING_TRANSACTION_TRANSMISSION' => 'Transacción no permitida',
			'PENDING_PAYMENT_IN_ENTITY' => 'Recibo de pago generado. En espera de pago',
			'PENDING_PAYMENT_IN_BANK' => 'Recibo de pago generado. En espera de pago',
			'PENDING_SENT_TO_FINANCIAL_ENTITY' => ' ',
			'PENDING_AWAITING_PSE_CONFIRMATION' => 'En espera de confirmación de PSE',
			'PENDING_NOTIFYING_ENTITY' => 'Recibo de pago generado. En espera de pago',

		];

		return (isset($statuses[$text])) ? $statuses[$text] : 'Otro error';

	}

} 


 ?>