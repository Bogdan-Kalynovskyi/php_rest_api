import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';
import { MdDialog, MdDialogRef } from '@angular/material';

import { Editor } from '../models/editor';
import { EditorService } from '../services/editor.service';

@Component({
    selector: 'app-editors',
    templateUrl: './editors.component.html',
    styleUrls: ['./editors.component.css'],
    providers: [EditorService]
})
export class EditorsComponent implements OnInit {
    selectedEditor: Editor;
    editors: Editor[];
    // dialogRef: MdDialogRef<SelectedHeroDialog>;

    constructor(
        private editorService: EditorService,
        private router: Router,
        private dialog: MdDialog) {
    }

    ngOnInit(): void {
        this.getEditors();
    }

    getEditors(): void {
        this.editorService.getEditors().then(editors =>
            this.editors = editors);
    }

    // onSelect(hero: Hero): void {
    //     this.dialogRef = this.dialog.open(SelectedHeroDialog);
    //     this.dialogRef.componentInstance.selectedHero = hero;
    // }

    add(name: string): void {
        name = name.trim();
        if (!name) { return; }
        this.editorService.create(name)
            .then(editor => {
                this.editors.push(editor);
                this.selectedEditor = null;
            });
    }

    delete(editor: Editor): void {
        this.editorService
            .delete(editor.id)
            .then(() => {
                this.editors = this.editors.filter(h => h !== editor);
                if (this.selectedEditor === editor) {
                    this.selectedEditor = null;
                }
            });
    }

}