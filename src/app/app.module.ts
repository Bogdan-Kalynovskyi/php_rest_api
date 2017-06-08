import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { RoutingModule } from './routing.module';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import { MaterialModule } from '@angular/material';

import { AppComponent } from './app.component';
import { EditorsComponent} from './editors/editors.component';

import { EditorService } from './services/editor.service';

@NgModule({
    declarations: [
        AppComponent,
        EditorsComponent
    ],
    imports: [
        BrowserModule,
        FormsModule,
        HttpModule,
        RoutingModule,
        BrowserAnimationsModule,
        MaterialModule
    ],
    providers: [EditorService],
    bootstrap: [AppComponent]
})
export class AppModule { }
