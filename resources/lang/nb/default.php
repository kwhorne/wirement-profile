<?php

return [
    'form' => [

        'name' => [
            'label' => 'Navn',
        ],

        'team_owner' => [
            'label' => 'Teameier',
        ],

        'email' => [
            'label' => 'E-post',
        ],

        'password' => [

            'label' => 'Passord',

            'error_message' => 'Det oppgitte passordet var feil.',

        ],

        'code' => [

            'label' => 'Kode',

            'hint' => 'Vennligst bekreft tilgang til kontoen din ved å oppgi autentiseringskoden fra din autentiseringsapp.',

            'error_message' => 'Den oppgitte tofaktor-autentiseringskoden er ugyldig.',

        ],

        'profile_photo' => [
            'label' => 'Bilde',
        ],

        'current_password' => [
            'label' => 'Nåværende passord',
        ],

        'new_password' => [
            'label' => 'Nytt passord',
        ],

        'confirm_password' => [
            'label' => 'Bekreft passord',
        ],

        'recovery_code' => [

            'label' => 'Gjenopprettingskode',

            'hint' => 'Vennligst bekreft tilgang til kontoen din ved å oppgi en av dine nødgjenopprettingskoder.',

        ],

        'token_name' => [
            'label' => 'Token-navn',
        ],

        'permissions' => [
            'label' => 'Tillatelser',
        ],

        'team_name' => [
            'label' => 'Teamnavn',
        ],

        'or' => [
            'label' => 'Eller ',
        ],

    ],

    'table' => [

        'columns' => [

            'token_name' => [
                'label' => 'Tokens',
            ],

            'pending_invitations' => [
                'label' => 'Ventende invitasjoner',
            ],

            'team_members' => [
                'label' => 'Medlemmer',
            ],

            'role' => [
                'label' => 'Rolle',
            ],

        ],

    ],

    'notification' => [

        'save' => [

            'success' => [
                'message' => 'Lagret.',
            ],

        ],

        'create_token' => [

            'success' => [
                'message' => 'Vennligst kopier ditt nye API-token. Av sikkerhetshensyn vil det ikke vises igjen.',
            ],

            'error' => [
                'message' => 'Velg minst én tillatelse.',
            ],

        ],

        'copy_token' => [

            'success' => [
                'message' => 'kopiert til utklippstavle',
            ],

        ],

        'token_deleted' => [

            'success' => [
                'message' => 'Token slettet!',
            ],

        ],

        'team_deleted' => [

            'success' => [
                'message' => 'Team slettet!',
            ],

        ],

        'team_member_removed' => [
            'success' => [
                'message' => 'Du har fjernet dette teammedlemmet.',
            ],
        ],

        'team_invitation_sent' => [
            'success' => [
                'message' => 'Teaminvitasjon sendt.',
            ],
        ],

        'team_invitation_cancelled' => [
            'success' => [
                'message' => 'Teaminvitasjon kansellert.',
            ],
        ],

        'leave_team' => [

            'success' => [
                'message' => 'Du har forlatt teamet.',
            ],

        ],

        'accepted_invitation' => [

            'success' => [

                'title' => 'Teaminvitasjon akseptert',

                'message' => 'Flott! Du har akseptert invitasjonen til å bli med i :team teamet.',

            ],
        ],

        'rate_limited' => [

            'title' => 'For mange forespørsler',

            'message' => 'Vennligst prøv igjen om :seconds sekunder',

        ],

        'logged_out_other_sessions' => [

            'success' => [
                'message' => 'Alle andre nettleserøkter har blitt logget ut.',
            ],

        ],

        'permission_denied' => [

            'cannot_update_team_member' => 'Du har ikke tillatelse til å oppdatere dette teammedlemmet.',

            'cannot_leave_team' => 'Du kan ikke forlate et team du opprettet.',

            'cannot_remove_team_member' => 'Du har ikke tillatelse til å fjerne dette teammedlemmet.',

            'cannot_delete_team' => 'Du har ikke tillatelse til å slette dette teamet.',

        ],
    ],

    'action' => [

        'save' => [
            'label' => 'Lagre',
        ],

        'confirm' => [
            'label' => 'Bekreft',
        ],

        'cancel' => [
            'label' => 'Avbryt',
        ],

        'disable' => [
            'label' => 'Deaktiver',
        ],

        'enable' => [
            'label' => 'Aktiver',
        ],

        'two_factor_authentication' => [

            'label' => [

                'regenerate_recovery_codes' => 'Generer nye gjenopprettingskoder',

                'use_recovery_code' => 'bruk en gjenopprettingskode',

                'use_authentication_code' => 'bruk en autentiseringskode',

                'logout' => 'Logg ut',

            ],

        ],

        'update_token' => [

            'title' => 'API Token-tillatelser',

            'label' => 'Tillatelser',

            'modal' => [
                'label' => 'Lagre',
            ],

        ],

        'delete_token' => [

            'title' => 'Slett API Token',

            'description' => 'Er du sikker på at du vil slette dette API-tokenet?',

            'label' => 'Fjern',

        ],

        'delete_account' => [

            'label' => 'Slett konto',

            'notice' => 'Er du sikker på at du vil slette kontoen din? Når kontoen er slettet, vil alle ressurser og data bli permanent slettet. Vennligst oppgi passordet ditt for å bekrefte at du vil slette kontoen permanent.',

        ],

        'delete_team' => [

            'label' => 'Slett team',

            'notice' => 'Er du sikker på at du vil slette dette teamet? Når et team er slettet, vil alle ressurser og data bli permanent slettet.',

        ],

        'create_token' => [
            'label' => 'Opprett token',
        ],

        'copy_token' => [
            'label' => 'Kopier',
        ],

        'add_team_member' => [

            'label' => 'Legg til',

            'error_message' => [

                'email_already_joined' => 'Denne brukeren tilhører allerede teamet.',

                'email_not_found' => 'Vi kunne ikke finne en registrert bruker med denne e-postadressen.',

                'email_already_invited' => 'Denne brukeren har allerede blitt invitert til teamet.',

            ],
        ],

        'update_team_role' => [
            'title' => 'Administrer rolle',
        ],

        'remove_team_member' => [

            'label' => 'Fjern',

            'notice' => 'Er du sikker på at du vil fjerne dette teammedlemmet?',
        ],

        'leave_team' => [

            'label' => 'Forlat',

            'notice' => 'Er du sikker på at du vil forlate dette teamet?',
        ],

        'resend_team_invitation' => [
            'label' => 'Send på nytt',
        ],

        'cancel_team_invitation' => [
            'label' => 'Avbryt',
        ],

        'log_out_other_browsers' => [

            'label' => 'Logg ut andre nettleserøkter',

            'title' => 'Logg ut andre nettleserøkter',

            'description' => 'Oppgi passordet ditt for å bekrefte at du vil logge ut av andre nettleserøkter på alle enhetene dine.',

        ],

    ],

    'mail' => [

        'team_invitation' => [

            'subject' => 'Teaminvitasjon',

            'message' => [
                'invitation' => 'Du har blitt invitert til å bli med i :team teamet!',

                'instruction' => 'Klikk på knappen nedenfor for å akseptere invitasjonen og komme i gang:',

                'notice' => 'Hvis du ikke forventet å motta en invitasjon til dette teamet, kan du ignorere denne e-posten.',
            ],

            'label' => [

                'create_account' => 'Opprett konto',

                'accept_invitation' => 'Aksepter invitasjon',

            ],

        ],

    ],

    'page' => [

        'create_team' => [

            'title' => 'Opprett team',

        ],

        'edit_team' => [

            'title' => 'Teaminnstillinger',

        ],

    ],

    'menu_item' => [

        'api_tokens' => [
            'label' => 'API Tokens',
        ],

    ],

    'profile_photo' => [
    ],

    'update_profile_information' => [

        'section' => [

            'title' => 'Profilinformasjon',

            'description' => 'Oppdater kontoens profilinformasjon og e-postadresse.',

        ],

    ],

    'update_password' => [

        'section' => [

            'title' => 'Oppdater passord',

            'description' => 'Sørg for at kontoen din bruker et langt, tilfeldig passord for å holde den sikker.',

        ],

    ],

    'two_factor_authentication' => [

        'section' => [

            'title' => 'Tofaktor-autentisering',

            'description' => 'Legg til ekstra sikkerhet til kontoen din ved å bruke tofaktor-autentisering.',

        ],

    ],

    'delete_account' => [

        'section' => [

            'title' => 'Slett konto',

            'description' => 'Slett kontoen din permanent.',

            'notice' => 'Når kontoen din er slettet, vil alle ressurser og data bli permanent slettet. Før du sletter kontoen, vennligst last ned data eller informasjon du ønsker å beholde.',

        ],

    ],

    'create_api_token' => [

        'section' => [

            'title' => 'Opprett API Token',

            'description' => 'API-tokens lar tredjepartstjenester autentisere med applikasjonen vår på dine vegne.',

        ],

    ],

    'manage_api_tokens' => [

        'section' => [

            'title' => 'Administrer API Tokens',

            'description' => 'Du kan slette eksisterende tokens hvis de ikke lenger er nødvendige.',

        ],

    ],

    'browser_sessions' => [

        'section' => [

            'title' => 'Nettleserøkter',

            'description' => 'Administrer og logg ut aktive økter på andre nettlesere og enheter.',

            'notice' => 'Om nødvendig kan du logge ut av alle andre nettleserøkter på alle enhetene dine. Noen av dine nylige økter er listet nedenfor, men denne listen er kanskje ikke uttømmende. Hvis du føler at kontoen din er kompromittert, bør du også oppdatere passordet ditt.',

            'labels' => [

                'current_device' => 'Denne enheten',

                'last_active' => 'Sist aktiv',

                'unknown_device' => 'Ukjent',

            ],

        ],

    ],

    'create_team' => [

        'section' => [

            'title' => 'Opprett team',

        ],

    ],

    'update_team_name' => [

        'section' => [

            'title' => 'Teamnavn',

            'description' => 'Teamets navn og eierinformasjon.',

        ],

    ],

    'add_team_member' => [

        'section' => [

            'title' => 'Legg til teammedlem',

            'description' => 'Legg til et nytt teammedlem til teamet ditt, slik at de kan samarbeide med deg.',

            'notice' => 'Vennligst oppgi e-postadressen til personen du vil legge til i dette teamet.',

        ],

    ],

    'team_members' => [

        'section' => [

            'title' => 'Teammedlemmer',

            'description' => 'Alle personene som er del av dette teamet.',

        ],

    ],

    'pending_team_invitations' => [

        'section' => [

            'title' => 'Ventende teaminvitasjoner',

            'description' => 'Disse personene har blitt invitert til teamet ditt og har fått tilsendt en invitasjons-e-post. De kan bli med i teamet ved å akseptere e-postinvitasjonen.',

        ],

    ],

    'delete_team' => [

        'section' => [

            'title' => 'Slett team',

            'description' => 'Slett dette teamet permanent.',

            'notice' => 'Når et team er slettet, vil alle ressurser og data bli permanent slettet. Før du sletter dette teamet, vennligst last ned data eller informasjon du ønsker å beholde.',

        ],

    ],

];
